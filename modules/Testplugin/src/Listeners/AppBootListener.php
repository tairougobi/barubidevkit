<?php

namespace ExamplePlugin\Listeners;

class AppBootListener
{
    public function handle()
    {
        // Logique à exécuter au démarrage de l'application
        error_log("ExamplePlugin: Application démarrée");
    }
}

