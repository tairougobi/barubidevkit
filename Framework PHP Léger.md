# Framework PHP Léger

Un framework PHP moderne, modulaire et ultra léger inspiré de Laravel et October CMS.

## Caractéristiques

- **Architecture MVC** : Séparation claire des responsabilités
- **Système de routage** : Routage flexible avec support des closures et contrôleurs
- **Injection de dépendances** : Conteneur de services léger compatible PSR-11
- **ORM léger** : QueryBuilder et modèles Eloquent-like
- **Moteur de templates** : Syntaxe Blade-like pour les vues
- **Système de plugins** : Architecture modulaire extensible
- **Système de thèmes** : Support des thèmes avec layouts
- **API REST intégrée** : Contrôleurs API avec authentification
- **Interface CLI** : Commandes artisan-like pour la génération de code

## Installation

1. Clonez le repository :
```bash
git clone <repository-url>
cd mon-framework
```

2. Installez les dépendances :
```bash
composer install
```

3. Configurez l'environnement :
```bash
cp .env.example .env
# Éditez le fichier .env selon vos besoins
```

## Démarrage rapide

### Démarrer le serveur de développement

```bash
./artisan serve
```

Le serveur sera accessible sur `http://127.0.0.1:8000`

### Créer un contrôleur

```bash
./artisan make:controller UserController
```

### Créer un modèle

```bash
./artisan make:model User
```

### Créer une migration

```bash
./artisan make:migration create_users_table
```

## Structure du projet

```
├── app/                    # Code de l'application
│   ├── Controllers/        # Contrôleurs
│   ├── Models/            # Modèles
│   └── Views/             # Vues/Templates
├── config/                # Fichiers de configuration
├── core/                  # Noyau du framework
│   ├── Api/              # Classes API
│   ├── Console/          # Interface CLI
│   ├── Container/        # Injection de dépendances
│   ├── Controller/       # Contrôleur de base
│   ├── Database/         # ORM et base de données
│   ├── Http/            # Classes HTTP
│   ├── Kernel/          # Noyau principal
│   ├── Model/           # Modèle de base
│   ├── Plugin/          # Système de plugins
│   ├── Routing/         # Système de routage
│   ├── Theme/           # Système de thèmes
│   └── View/            # Moteur de templates
├── modules/              # Modules/Plugins
├── public/              # Point d'entrée web
├── routes/              # Fichiers de routes
└── themes/              # Thèmes
```

## Routage

### Routes basiques

```php
// routes/web.php
use Core\Routing\Router;

$router->get('/', function($request) {
    return new Response('Hello World!');
});

$router->get('/users', 'UserController@index');
$router->post('/users', 'UserController@store');
```

### Paramètres de route

```php
$router->get('/user/{id}', function($request, $id) {
    return new Response("User ID: {$id}");
});
```

## Contrôleurs

```php
<?php

namespace App\Controllers;

use Core\Controller\Controller;

class UserController extends Controller
{
    public function index()
    {
        return $this->view('users.index', [
            'users' => User::all()
        ]);
    }
    
    public function show($id)
    {
        $user = User::find($id);
        return $this->view('users.show', compact('user'));
    }
}
```

## Modèles

```php
<?php

namespace App\Models;

use Core\Model\Model;

class User extends Model
{
    protected string $table = 'users';
    
    protected array $fillable = [
        'name', 'email', 'password'
    ];
    
    protected array $hidden = [
        'password'
    ];
}
```

## Vues

Les vues utilisent une syntaxe Blade-like :

```html
<!-- app/Views/users/index.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Users</title>
</head>
<body>
    <h1>Users List</h1>
    
    @if(count($users) > 0)
        <ul>
            @foreach($users as $user)
                <li>{{ $user->name }} - {{ $user->email }}</li>
            @endforeach
        </ul>
    @else
        <p>No users found.</p>
    @endif
</body>
</html>
```

## Base de données

### Configuration

Configurez votre base de données dans `config/database.php` :

```php
return [
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => $_ENV['DB_HOST'] ?? 'localhost',
            'database' => $_ENV['DB_DATABASE'] ?? 'framework',
            'username' => $_ENV['DB_USERNAME'] ?? 'root',
            'password' => $_ENV['DB_PASSWORD'] ?? '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ]
    ]
];
```

### QueryBuilder

```php
use Core\Database\QueryBuilder;

$users = QueryBuilder::table('users')
    ->where('active', 1)
    ->orderBy('name')
    ->get();
```

## API REST

### Contrôleurs API

```php
<?php

namespace App\Api;

use Core\Api\ApiController;

class UserApiController extends ApiController
{
    public function index()
    {
        $users = User::all();
        return $this->json($users);
    }
    
    public function store()
    {
        $data = $this->request->json();
        $user = User::create($data);
        return $this->json($user, 201);
    }
}
```

### Routes API

```php
// routes/api.php
$router->get('/api/users', 'App\\Api\\UserApiController@index');
$router->post('/api/users', 'App\\Api\\UserApiController@store');
```

## Plugins/Modules

### Créer un plugin

```php
<?php
// modules/MyPlugin/plugin.php

return [
    'name' => 'My Plugin',
    'version' => '1.0.0',
    'description' => 'Description du plugin',
    'author' => 'Votre nom',
    'bootstrap' => 'bootstrap.php'
];
```

### Bootstrap du plugin

```php
<?php
// modules/MyPlugin/bootstrap.php

use Core\Plugin\PluginManager;

PluginManager::registerListener('app.boot', function() {
    // Code d'initialisation du plugin
});
```

## Thèmes

### Structure d'un thème

```
themes/montheme/
├── theme.json          # Configuration du thème
├── views/
│   └── layouts/
│       └── default.php # Layout principal
├── assets/
│   ├── css/
│   └── js/
```

### Configuration du thème

```json
{
    "name": "Mon Thème",
    "version": "1.0.0",
    "description": "Description du thème",
    "author": "Votre nom"
}
```

## Interface CLI

### Commandes disponibles

```bash
# Créer un contrôleur
./artisan make:controller NomController

# Créer un modèle
./artisan make:model NomModel

# Créer une migration
./artisan make:migration nom_migration

# Démarrer le serveur
./artisan serve
```

### Créer une commande personnalisée

```php
<?php

namespace App\Console\Commands;

use Core\Console\Command;

class CustomCommand extends Command
{
    protected string $name = 'custom:command';
    protected string $description = 'Description de la commande';
    
    public function handle(array $args = []): int
    {
        $this->info('Commande exécutée avec succès !');
        return 0;
    }
}
```

## Configuration

### Variables d'environnement

```env
APP_NAME="Mon Framework"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=framework
DB_USERNAME=root
DB_PASSWORD=

THEME_DEFAULT=default
```

## Tests

Le framework est testé avec les routes suivantes :

- `GET /` : Page d'accueil
- `GET /hello` : Message de bienvenue
- `GET /welcome` : Vue avec template
- `GET /test-controller` : Test du contrôleur

## Contribution

1. Fork le projet
2. Créez une branche pour votre fonctionnalité
3. Committez vos changements
4. Poussez vers la branche
5. Ouvrez une Pull Request

## Licence

Ce projet est sous licence MIT.

