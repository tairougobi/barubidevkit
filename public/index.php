<?php

// Point d'entrée principal de l'application
require_once __DIR__ . '/../bootstrap.php';

use Core\Kernel\Kernel;
use Core\Http\Request;

try {
    // Créer une instance du kernel
    $kernel = new Kernel();
    
    // Créer une requête à partir des données globales
    $request = Request::createFromGlobals();
    
    // Traiter la requête et obtenir une réponse
    $response = $kernel->handle($request);
    
    // Envoyer la réponse au navigateur
    $response->send();
    
} catch (Exception $e) {
    // Gestion d'erreur basique
    http_response_code(500);
    echo 'Erreur interne du serveur: ' . $e->getMessage();
}

