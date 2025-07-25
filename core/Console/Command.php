<?php

namespace Core\Console;

use Core\Container\Container;

abstract class Command
{
    protected string $name;
    protected string $description;
    protected Container $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    abstract public function handle(array $args = []): int;

    protected function info(string $message): void
    {
        echo "\033[32m" . $message . "\033[0m\n";
    }

    protected function error(string $message): void
    {
        echo "\033[31m" . $message . "\033[0m\n";
    }

    protected function comment(string $message): void
    {
        echo "\033[33m" . $message . "\033[0m\n";
    }
}

