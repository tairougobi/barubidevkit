<?php

namespace Core\Database;

abstract class Migration
{
    protected Connection $connection;

    public function __construct()
    {
        $config = require CONFIG_PATH . 
'/database.php';
        $connectionConfig = $config["connections"][$config["default"]];
        $this->connection = new Connection($connectionConfig);
    }

    abstract public function up(): void;
    abstract public function down(): void;

    protected function createTable(string $tableName, callable $callback): void
    {
        $schema = new Schema($this->connection, $tableName);
        $callback($schema);
        $schema->create();
    }

    protected function dropTable(string $tableName): void
    {
        $this->connection->execute("DROP TABLE IF EXISTS {$tableName}");
    }
}

