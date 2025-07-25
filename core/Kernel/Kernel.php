<?php

namespace Core\Kernel;

use Core\Http\Request;
use Core\Http\Response;
use Core\Routing\Router;

class Kernel
{
    private Router $router;

    public function __construct()
    {
        $this->router = $GLOBALS['app']->get(Router::class);
        $this->loadRoutes();
    }

    public function handle(Request $request): Response
    {
        try {
            return $this->router->dispatch($request);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    private function loadRoutes(): void
    {
        $routeFiles = [
            ROUTES_PATH . '/web.php',
            ROUTES_PATH . '/api.php'
        ];

        foreach ($routeFiles as $file) {
            if (file_exists($file)) {
                require_once $file;
            }
        }
    }

    private function handleException(\Exception $e): Response
    {
        if ($_ENV['APP_DEBUG'] ?? false) {
            return new Response(
                "Erreur: " . $e->getMessage() . "\n" . $e->getTraceAsString(),
                500
            );
        }

        return new Response('Erreur interne du serveur', 500);
    }

    public function getRouter(): Router
    {
        return $this->router;
    }
}

