<?php

namespace Core\Console\Commands;

use Core\Console\Command;

class ServeCommand extends Command
{
    protected string $name = 'serve';
    protected string $description = 'Démarrer le serveur de développement';

    public function handle(array $args = []): int
    {
        $host = $args[0] ?? '127.0.0.1';
        $port = $args[1] ?? '8000';

        $this->info("Démarrage du serveur sur http://{$host}:{$port}");
        $this->comment("Appuyez sur Ctrl+C pour arrêter le serveur");

        $command = "php -S {$host}:{$port} -t " . PUBLIC_PATH . " " . PUBLIC_PATH . "/router.php";
        
        // Exécuter le serveur
        passthru($command);
        
        return 0;
    }
}

