<?php

namespace Core\Http;

class Request
{
    private array $parameters;
    private string $method;
    private string $uri;

    public function __construct(array $parameters, string $method, string $uri)
    {
        $this->parameters = $parameters;
        $this->method = $method;
        $this->uri = $uri;
    }

    public static function createFromGlobals(): self
    {
        $uri = strtok($_SERVER["REQUEST_URI"], "?");
        $method = $_SERVER["REQUEST_METHOD"];
        $parameters = $_REQUEST;

        return new self($parameters, $method, $uri);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    // public function getUri(): string
    // {
    //     return $this->uri;
    // }

    public function getUri(): string
    {
        $uri = $this->uri;

        // Enlever slash final sauf si c’est la racine "/"
        if ($uri !== '/' && substr($uri, -1) === '/') {
            $uri = rtrim($uri, '/');
        }

        // Ici tu peux enlever par exemple "index.php" si tu as ce problème
        // $uri = str_replace('/index.php', '', $uri);

        return $uri;
    }


    public function get(string $key, $default = null)
    {
        return $this->parameters[$key] ?? $default;
    }

    public function all(): array
    {
        return $this->parameters;
    }
}

