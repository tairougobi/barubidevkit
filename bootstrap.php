<?php

use Core\Routing\Router;
use Core\Http\Request;
use Core\Http\Response;
use App\Controllers\WelcomeController;

// Définir les chemins de base
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('CORE_PATH', BASE_PATH . '/core');
define('CONFIG_PATH', BASE_PATH . '/config');
define('ROUTES_PATH', BASE_PATH . '/routes');
define('MODULES_PATH', BASE_PATH . '/modules');
define('PUBLIC_PATH', BASE_PATH . '/public');

// Charger l'autoloader Composer
require_once BASE_PATH . '/vendor/autoload.php';
require_once __DIR__ . '/core/helpers.php';

// Charger les variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

// Charger les fichiers de configuration
foreach (glob(CONFIG_PATH . '/*.php') as $filename) {
    require_once $filename;
}

// Initialiser le conteneur
$app = new Core\Container\Container();

// Enregistrer le routeur
$app->set(Router::class, function () {
    return new Router();
});

$router = $app->get(Router::class);

// Charger les routes manuelles
require_once ROUTES_PATH . '/web.php';

$router->loadThemePages();

// Rendre le conteneur global
$GLOBALS['app'] = $app;

