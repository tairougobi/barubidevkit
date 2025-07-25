<?php

namespace Core\View;

use Core\Http\Response;

class View
{
    private string $viewName;
    private array $data;
    private TemplateEngine $templateEngine;

    public function __construct(string $viewName, array $data = [])
    {
        $this->viewName = $viewName;
        $this->data = $data;
        $this->templateEngine = new TemplateEngine();
    }

    public function render(): string
    {
        // Utiliser le TemplateEngine pour rendre la vue
        return $this->templateEngine->render($this->viewName, $this->data);
    }

    public static function make(string $view, array $data = []): Response
    {
        $instance = new static($view, $data);
        return new Response($instance->render());
    }
}

