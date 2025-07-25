<?php

return [
    'name' => $_ENV['APP_NAME'] ?? 'MonFramework',
    'env' => $_ENV['APP_ENV'] ?? 'production',
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
    'url' => $_ENV['APP_URL'] ?? 'http://localhost',
    
    'timezone' => 'Europe/Paris',
    
    'providers' => [
        // Liste des fournisseurs de services
    ],
    
    'aliases' => [
        // Alias de classes
    ],
];

