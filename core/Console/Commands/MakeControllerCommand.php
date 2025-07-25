<?php

namespace Core\Console\Commands;

use Core\Console\Command;

class MakeControllerCommand extends Command
{
    protected string $name = 'make:controller';
    protected string $description = 'Créer un nouveau contrôleur';

    public function handle(array $args = []): int
    {
        if (empty($args[0])) {
            $this->error('Nom du contrôleur requis');
            return 1;
        }

        $controllerName = $args[0];
        $controllerPath = APP_PATH . '/Controllers/' . $controllerName . '.php';

        if (file_exists($controllerPath)) {
            $this->error("Le contrôleur {$controllerName} existe déjà");
            return 1;
        }

        // Créer le dossier s'il n'existe pas
        $dir = dirname($controllerPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $template = $this->getControllerTemplate($controllerName);
        file_put_contents($controllerPath, $template);

        $this->info("Contrôleur {$controllerName} créé avec succès");
        return 0;
    }

    private function getControllerTemplate(string $name): string
    {
        return "<?php

namespace App\Controllers;

use Core\Controller\Controller;
use Core\Http\Request;
use Core\Http\Response;

class {$name} extends Controller
{
    public function index(Request \$request): Response
    {
        return \$this->render('index', [
            'title' => '{$name}'
        ]);
    }
}
";
    }
}

