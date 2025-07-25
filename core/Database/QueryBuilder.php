<?php

namespace Core\Database;

class QueryBuilder
{
    private Connection $connection;
    private string $table;
    private array $wheres = [];
    private array $selects = ['*'];
    private array $joins = [];
    private array $orders = [];
    private ?int $limit = null;
    private ?int $offset = null;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function table(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    public function select($columns = ['*']): self
    {
        $this->selects = is_array($columns) ? $columns : func_get_args();
        return $this;
    }

    public function where(string $column, string $operator, $value): self
    {
        $this->wheres[] = compact('column', 'operator', 'value');
        return $this;
    }

    public function join(string $table, string $first, string $operator, string $second): self
    {
        $this->joins[] = compact('table', 'first', 'operator', 'second');
        return $this;
    }

    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->orders[] = compact('column', 'direction');
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    public function get(): array
    {
        $sql = $this->buildSelectQuery();
        $params = $this->getWhereParams();
        
        return $this->connection->query($sql, $params);
    }

    public function first(): ?array
    {
        $this->limit(1);
        $results = $this->get();
        return $results[0] ?? null;
    }

    public function insert(array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        
        $this->connection->execute($sql, $data);
        return (int) $this->connection->lastInsertId();
    }

    public function update(array $data): int
    {
        $sets = [];
        foreach (array_keys($data) as $column) {
            $sets[] = "{$column} = :{$column}";
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets);
        
        if (!empty($this->wheres)) {
            $sql .= ' WHERE ' . $this->buildWhereClause();
        }
        
        $params = array_merge($data, $this->getWhereParams());
        
        return $this->connection->execute($sql, $params);
    }

    public function delete(): int
    {
        $sql = "DELETE FROM {$this->table}";
        
        if (!empty($this->wheres)) {
            $sql .= ' WHERE ' . $this->buildWhereClause();
        }
        
        return $this->connection->execute($sql, $this->getWhereParams());
    }

    private function buildSelectQuery(): string
    {
        $sql = 'SELECT ' . implode(', ', $this->selects) . " FROM {$this->table}";
        
        foreach ($this->joins as $join) {
            $sql .= " JOIN {$join['table']} ON {$join['first']} {$join['operator']} {$join['second']}";
        }
        
        if (!empty($this->wheres)) {
            $sql .= ' WHERE ' . $this->buildWhereClause();
        }
        
        if (!empty($this->orders)) {
            $orderClauses = [];
            foreach ($this->orders as $order) {
                $orderClauses[] = "{$order['column']} {$order['direction']}";
            }
            $sql .= ' ORDER BY ' . implode(', ', $orderClauses);
        }
        
        if ($this->limit) {
            $sql .= " LIMIT {$this->limit}";
        }
        
        if ($this->offset) {
            $sql .= " OFFSET {$this->offset}";
        }
        
        return $sql;
    }

    private function buildWhereClause(): string
    {
        $clauses = [];
        foreach ($this->wheres as $index => $where) {
            $clauses[] = "{$where['column']} {$where['operator']} :where_{$index}";
        }
        return implode(' AND ', $clauses);
    }

    private function getWhereParams(): array
    {
        $params = [];
        foreach ($this->wheres as $index => $where) {
            $params["where_{$index}"] = $where['value'];
        }
        return $params;
    }
}

