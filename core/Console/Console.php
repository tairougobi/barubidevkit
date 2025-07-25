<?php

namespace Core\Console;

use Core\Container\Container;

class Console
{
    private Container $app;
    private array $commands = [];

    public function __construct(Container $app)
    {
        $this->app = $app;
        $this->registerDefaultCommands();
    }

    private function registerDefaultCommands(): void
    {
        $this->register(new Commands\MakeControllerCommand($this->app));
        $this->register(new Commands\MakeModelCommand($this->app));
        $this->register(new Commands\MakeMigrationCommand($this->app));
        $this->register(new Commands\ServeCommand($this->app));
        $this->register(new Commands\LinkThemesCommand($this->app));

    }

    public function register(Command $command): void
    {
        $this->commands[$command->getName()] = $command;
    }

    public function run(array $argv): int
    {
        if (count($argv) < 2) {
            $this->showHelp();
            return 0;
        }

        $commandName = $argv[1];
        $args = array_slice($argv, 2);

        if ($commandName === 'help' || $commandName === '--help' || $commandName === '-h') {
            $this->showHelp();
            return 0;
        }

        if (!isset($this->commands[$commandName])) {
            echo "Commande '{$commandName}' non trouvée.\n";
            $this->showHelp();
            return 1;
        }

        return $this->commands[$commandName]->handle($args);
    }

    private function showHelp(): void
    {
        echo "MonFramework CLI\n\n";
        echo "Usage:\n";
        echo "  php artisan <command> [arguments]\n\n";
        echo "Commandes disponibles:\n";

        foreach ($this->commands as $name => $command) {
            echo sprintf("  %-20s %s\n", $name, $command->getDescription());
        }
    }
}

