<?php

namespace Core\Model;

use Core\Database\Connection;
use Core\Database\QueryBuilder;

abstract class Model
{
    protected static string $table;
    protected string $primaryKey = 'id';
    protected array $fillable = [];
    protected array $attributes = [];
    protected bool $exists = false;

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    public static function getConnection(): Connection
    {
        // Récupérer la configuration de la base de données
        $config = require CONFIG_PATH . '/database.php';
        $connectionConfig = $config['connections'][$config['default']];
        
        return new Connection($connectionConfig);
    }

    public static function query(): QueryBuilder
    {
        $builder = new QueryBuilder(static::getConnection());
        return $builder->table(static::getTable());
    }

    public static function getTable(): string
    {
        return static::$table ?? strtolower(class_basename(static::class)) . 's';
    }

    public static function all(): array
    {
        $results = static::query()->get();
        return array_map(fn($item) => new static($item), $results);
    }

    public static function find($id): ?static
    {
        $result = static::query()->where('id', '=', $id)->first();
        return $result ? new static($result) : null;
    }

    public static function where(string $column, string $operator, $value): QueryBuilder
    {
        return static::query()->where($column, $operator, $value);
    }

    public function save(): bool
    {
        if ($this->exists) {
            return $this->update();
        }
        
        return $this->insert();
    }

    protected function insert(): bool
    {
        $data = $this->getAttributesForSave();
        $id = static::query()->insert($data);
        
        if ($id) {
            $this->setAttribute($this->primaryKey, $id);
            $this->exists = true;
            return true;
        }
        
        return false;
    }

    protected function update(): bool
    {
        $data = $this->getAttributesForSave();
        $primaryKeyValue = $this->getAttribute($this->primaryKey);
        
        $affected = static::query()
            ->where($this->primaryKey, '=', $primaryKeyValue)
            ->update($data);
        
        return $affected > 0;
    }

    public function delete(): bool
    {
        if (!$this->exists) {
            return false;
        }
        
        $primaryKeyValue = $this->getAttribute($this->primaryKey);
        
        $affected = static::query()
            ->where($this->primaryKey, '=', $primaryKeyValue)
            ->delete();
        
        if ($affected > 0) {
            $this->exists = false;
            return true;
        }
        
        return false;
    }

    public function fill(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            if ($this->isFillable($key)) {
                $this->setAttribute($key, $value);
            }
        }
        
        return $this;
    }

    protected function isFillable(string $key): bool
    {
        return in_array($key, $this->fillable) || empty($this->fillable);
    }

    public function setAttribute(string $key, $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    public function getAttribute(string $key)
    {
        return $this->attributes[$key] ?? null;
    }

    protected function getAttributesForSave(): array
    {
        $attributes = $this->attributes;
        unset($attributes[$this->primaryKey]);
        return $attributes;
    }

    public function __get(string $key)
    {
        return $this->getAttribute($key);
    }

    public function __set(string $key, $value): void
    {
        $this->setAttribute($key, $value);
    }
}

function class_basename(string $class): string
{
    $class = is_object($class) ? get_class($class) : $class;
    return basename(str_replace('\\', '/', $class));
}

