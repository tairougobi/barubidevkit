<?php

namespace Core\Console\Commands;

use Core\Console\Command;

class MakeModelCommand extends Command
{
    protected string $name = 'make:model';
    protected string $description = 'Créer un nouveau modèle';

    public function handle(array $args = []): int
    {
        if (empty($args[0])) {
            $this->error('Nom du modèle requis');
            return 1;
        }

        $modelName = $args[0];
        $modelPath = APP_PATH . '/Models/' . $modelName . '.php';

        if (file_exists($modelPath)) {
            $this->error("Le modèle {$modelName} existe déjà");
            return 1;
        }

        // Créer le dossier s'il n'existe pas
        $dir = dirname($modelPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $template = $this->getModelTemplate($modelName);
        file_put_contents($modelPath, $template);

        $this->info("Modèle {$modelName} créé avec succès");
        return 0;
    }

    private function getModelTemplate(string $name): string
    {
        $tableName = strtolower($name) . 's';
        
        return "<?php

namespace App\Models;

use Core\Model\Model;

class {$name} extends Model
{
    protected static string \$table = '{$tableName}';
    
    protected array \$fillable = [
        // Ajoutez ici les champs autorisés pour l'assignation en masse
    ];
}
";
    }
}

