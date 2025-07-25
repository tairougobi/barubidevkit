<?php

namespace Core\Routing;

use Core\Http\Request;
use Core\Http\Response;

class Router
{
    private array $routes = [];
    private array $middlewares = [];
    private string $currentGroup = '';

    public function get(string $uri, $handler): void
    {
        $this->addRoute('GET', $uri, $handler);
    }

    public function post(string $uri, $handler): void
    {
        $this->addRoute('POST', $uri, $handler);
    }

    public function put(string $uri, $handler): void
    {
        $this->addRoute('PUT', $uri, $handler);
    }

    public function delete(string $uri, $handler): void
    {
        $this->addRoute('DELETE', $uri, $handler);
    }

    public function group(array $attributes, callable $callback): void
    {
        $prefix = $attributes['prefix'] ?? '';
        $middleware = $attributes['middleware'] ?? [];

        $previousGroup = $this->currentGroup;
        $previousMiddlewares = $this->middlewares;

        $this->currentGroup = $previousGroup . $prefix;
        $this->middlewares = array_merge($this->middlewares, (array) $middleware);

        $callback($this);

        $this->currentGroup = $previousGroup;
        $this->middlewares = $previousMiddlewares;
    }

    private function addRoute(string $method, string $uri, $handler): void
    {
        $fullUri = $this->currentGroup . $uri;
        $this->routes[] = [
            'method' => $method,
            'uri' => $fullUri,
            'handler' => $handler,
            'middlewares' => $this->middlewares
        ];
    }

//    public function dispatch(Request $request): Response
//     {
//         $method = $request->getMethod();
//         $uri = $request->getUri();

//         error_log("Dispatching $method $uri");

//         foreach ($this->routes as $route) {
//             error_log("Checking route: {$route['method']} {$route['uri']}");
//             if ($this->matchRoute($route, $method, $uri)) {
//                 error_log("Route matched: {$route['uri']}");
//                 return $this->executeRoute($route, $request);
//             }
//         }

//         return new Response('404 - Page non trouvée', 404);
//     }

    public function dispatch(Request $request): Response
    {
        $method = $request->getMethod();
        $uri = $request->getUri();

        foreach ($this->routes as $route) {
            if ($this->matchRoute($route, $method, $uri)) {
                return $this->executeRoute($route, $request);
            }
        }
        $themeManager = new \Core\Theme\ThemeManager();
        $templateEngine = new \Core\View\TemplateEngine();

        $path404 = $themeManager->getThemePath() . '/pages/404.htm';

        if (file_exists($path404)) {
            $html = $templateEngine->renderPage($path404);
            return new Response($html, 404);
        }

        return new Response('404 - Page non trouvée', 404);
    }

    private function matchRoute(array $route, string $method, string $uri): bool
    {
        if ($route['method'] !== $method) {
            return false;
        }

        $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $route['uri']);
        $pattern = '#^' . $pattern . '$#';

        return preg_match($pattern, $uri);
    }

    private function executeRoute(array $route, Request $request): Response
    {
        $handler = $route['handler'];

        // Exécuter les middlewares
        foreach ($route['middlewares'] as $middleware) {
            // Logique de middleware à implémenter
        }

        if (is_callable($handler)) {
            $result = $handler($request);
        } elseif (is_string($handler) && strpos($handler, '@') !== false) {
            [$controller, $method] = explode('@', $handler);
            $controllerInstance = new $controller();
            $result = $controllerInstance->$method($request);
        } else {
            throw new \Exception("Handler de route invalide");
        }

        if ($result instanceof Response) {
            return $result;
        }

        return new Response((string) $result);
    }
    
    public function loadThemePages(): void
    {
        $theme = new \Core\Theme\ThemeManager();
        $engine = new \Core\View\TemplateEngine();

        $pageFiles = glob($theme->getThemePath() . '/pages/*.htm');

        foreach ($pageFiles as $file) {
            $content = file_get_contents($file);
            if (!str_contains($content, '==')) {
                continue;
            }

            [$yaml, ] = explode('==', $content, 2);
            $meta = \Symfony\Component\Yaml\Yaml::parse($yaml);

            if (!isset($meta['url'])) {
                continue;
            }

            $url = $meta['url'];

            $this->get($url, function(Request $request) use ($engine, $file) {
                $html = $engine->renderPage($file);
                return new \Core\Http\Response($html, 200);
            });
        }
    }
    

}
