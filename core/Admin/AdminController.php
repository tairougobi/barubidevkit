<?php

namespace Core\Admin;

use Core\Controller\Controller;
use Core\Http\Response;

class AdminController extends Controller
{
    protected string $layout = 'admin';

    public function __construct()
    {
        parent::__construct();
        // Logique d'authentification et d'autorisation pour l'admin
        // ...
    }

    protected function renderAdmin(string $view, array $data = []): Response
    {
        // Utiliser le moteur de templates pour les vues d'administration
        // et le layout 'admin'
        return $this->template("layouts.{$this->layout}", ['content' => $this->templateEngine->render($view, $data)]);
    }

    // Méthodes pour la gestion des ressources (CRUD) peuvent être ajoutées ici
}

