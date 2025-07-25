<?php

namespace Core\Controller;

use Core\Http\Request;
use Core\Http\Response;
use Core\View\View;
use Core\View\TemplateEngine;

class Controller
{
    protected Request $request;
    protected TemplateEngine $templateEngine;

    public function __construct()
    {
        $this->request = Request::createFromGlobals();
        $this->templateEngine = new TemplateEngine();
    }

    protected function render(string $view, array $data = []): Response
    {
        return View::make($view, $data);
    }

    protected function template(string $template, array $data = []): Response
    {
        $content = $this->templateEngine->render($template, $data);
        return new Response($content);
    }

    protected function json(array $data, int $statusCode = 200): Response
    {
        return Response::json($data, $statusCode);
    }

    protected function redirect(string $url, int $statusCode = 302): Response
    {
        return new Response("", $statusCode, ["Location" => $url]);
    }
}

