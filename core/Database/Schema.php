<?php

namespace Core\Database;

class Schema
{
    private Connection $connection;
    private string $tableName;
    private array $columns = [];

    public function __construct(Connection $connection, string $tableName)
    {
        $this->connection = $connection;
        $this->tableName = $tableName;
    }

    public function id(string $name = 'id'): self
    {
        $this->columns[] = "{$name} INT AUTO_INCREMENT PRIMARY KEY";
        return $this;
    }

    public function string(string $name, int $length = 255): self
    {
        $this->columns[] = "{$name} VARCHAR({$length})";
        return $this;
    }

    public function text(string $name): self
    {
        $this->columns[] = "{$name} TEXT";
        return $this;
    }

    public function integer(string $name): self
    {
        $this->columns[] = "{$name} INT";
        return $this;
    }

    public function boolean(string $name): self
    {
        $this->columns[] = "{$name} BOOLEAN DEFAULT FALSE";
        return $this;
    }

    public function timestamp(string $name): self
    {
        $this->columns[] = "{$name} TIMESTAMP";
        return $this;
    }

    public function timestamps(): self
    {
        $this->columns[] = "created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
        $this->columns[] = "updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
        return $this;
    }

    public function create(): void
    {
        $sql = "CREATE TABLE {$this->tableName} (" . implode(', ', $this->columns) . ")";
        $this->connection->execute($sql);
    }
}

