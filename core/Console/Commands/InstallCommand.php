<?php

namespace Core\Console\Commands;
use Core\Console\Command;

class InstallCommand extends Command
{
    protected string $name = 'install:barubi';
    protected string $description = 'Installe le framework BarubiDevKit';
   public function handle(array $args = []): int
    {
        
        echo "Bienvenue dans l'installation de BarubiDevKit\n\n";

        $appName = $this->ask("Nom de l'application", "BarubiApp");
        $appUrl = $this->ask("URL de l'application", "http://localhost");

        $dbHost = $this->ask("Hôte de la base de données", "127.0.0.1");
        $dbName = $this->ask("Nom de la base", "barubi_db");
        $dbUser = $this->ask("Utilisateur DB", "root");
        $dbPass = $this->ask("Mot de passe DB", "");

        $envContent = <<<ENV
    APP_NAME={$appName}
    APP_ENV=local
    APP_DEBUG=true
    APP_URL={$appUrl}

    DB_HOST={$dbHost}
    DB_DATABASE={$dbName}
    DB_USERNAME={$dbUser}
    DB_PASSWORD={$dbPass}
    ENV;

        file_put_contents('.env', $envContent);

        echo "\n✅ Fichier .env généré avec succès.\n";

        return 0; 
    }


    private function ask(string $question, string $default = ''): string
    {
        $prompt = $default ? "$question [$default]: " : "$question: ";
        
        echo $prompt;

        $input = trim(fgets(STDIN));
        return $input === '' ? $default : $input;
    }

}
