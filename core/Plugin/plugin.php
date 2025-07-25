<?php

return [
    'name' => 'ExamplePlugin',
    'version' => '1.0.0',
    'description' => 'Un plugin d\'exemple pour démontrer le système de plugins',
    'author' => 'MonFramework',
    
    'autoload' => [
        'ExamplePlugin\\' => 'src/',
    ],
    
    'bootstrap' => 'bootstrap.php',
    
    'events' => [
        'app.boot' => [
            'ExamplePlugin\\Listeners\\AppBootListener@handle'
        ],
        'user.created' => [
            'ExamplePlugin\\Listeners\\UserCreatedListener@handle'
        ]
    ],
    
    'routes' => 'routes.php',
    
    'config' => [
        'enabled' => true,
        'settings' => [
            'example_setting' => 'example_value'
        ]
    ]
];

