# Architecture du Framework

## Vue d'ensemble

Ce framework PHP suit une architecture MVC (Model-View-Controller) modulaire avec injection de dépendances et un système de plugins extensible.

## Composants principaux

### 1. Kernel (Noyau)

Le kernel (`Core\Kernel\Kernel`) est le cœur du framework qui :
- Initialise l'application
- Charge les routes
- Traite les requêtes HTTP
- Gère les exceptions
- Retourne les réponses

### 2. Système de routage

Le routeur (`Core\Routing\Router`) :
- Enregistre les routes avec leurs méthodes HTTP
- Résout les routes avec paramètres
- Supporte les closures et les contrôleurs
- Gère les middlewares (à implémenter)

### 3. Conteneur de services

Le conteneur (`Core\Container\Container`) :
- Implémente PSR-11 (Container Interface)
- Gère l'injection de dépendances
- Supporte les singletons et les factories
- Résolution automatique des dépendances

### 4. Système HTTP

#### Request (`Core\Http\Request`)
- Encapsule les données de la requête HTTP
- Accès aux paramètres GET/POST
- Gestion des headers
- Support JSON

#### Response (`Core\Http\Response`)
- Encapsule la réponse HTTP
- Gestion des status codes
- Headers personnalisés
- Support JSON

### 5. ORM et Base de données

#### Connection (`Core\Database\Connection`)
- Gestion des connexions PDO
- Pool de connexions
- Configuration multiple

#### QueryBuilder (`Core\Database\QueryBuilder`)
- Interface fluide pour les requêtes SQL
- Support des jointures, conditions, tri
- Prévention des injections SQL

#### Model (`Core\Model\Model`)
- Active Record pattern
- Relations entre modèles
- Mutateurs et accesseurs
- Validation des données

### 6. Moteur de templates

#### TemplateEngine (`Core\View\TemplateEngine`)
- Syntaxe Blade-like
- Compilation des templates
- Mise en cache des vues compilées
- Directives personnalisées

#### View (`Core\View\View`)
- Rendu des vues
- Passage de données aux templates
- Intégration avec le moteur de templates

### 7. Système de plugins

#### PluginManager (`Core\Plugin\PluginManager`)
- Chargement dynamique des plugins
- Système d'événements
- Hooks et filtres
- Isolation des plugins

#### Plugin (`Core\Plugin\Plugin`)
- Classe de base pour les plugins
- Cycle de vie des plugins
- Configuration et métadonnées

### 8. Système de thèmes

#### ThemeManager (`Core\Theme\ThemeManager`)
- Gestion des thèmes actifs
- Chargement des assets
- Override des vues
- Configuration des thèmes

### 9. API REST

#### ApiController (`Core\Api\ApiController`)
- Classe de base pour les APIs
- Sérialisation JSON
- Gestion des erreurs API
- Codes de statut HTTP

#### ApiAuth (`Core\Api\ApiAuth`)
- Authentification API
- Tokens JWT (à implémenter)
- Rate limiting (à implémenter)

### 10. Interface CLI

#### Console (`Core\Console\Console`)
- Point d'entrée pour les commandes
- Parsing des arguments
- Gestion des options

#### Command (`Core\Console\Command`)
- Classe de base pour les commandes
- Interface utilisateur CLI
- Validation des arguments

## Flux de requête

1. **Point d'entrée** : `public/index.php`
2. **Bootstrap** : Chargement de `bootstrap.php`
3. **Initialisation** : Création du kernel
4. **Requête** : Création de l'objet Request
5. **Routage** : Résolution de la route
6. **Contrôleur** : Exécution de l'action
7. **Vue** : Rendu du template
8. **Réponse** : Envoi au navigateur

## Patterns utilisés

### Dependency Injection
- Inversion de contrôle
- Résolution automatique
- Configuration par code

### Active Record
- Modèles représentant les tables
- Méthodes CRUD intégrées
- Relations automatiques

### Front Controller
- Point d'entrée unique
- Routage centralisé
- Middleware pipeline

### Observer Pattern
- Système d'événements
- Hooks pour les plugins
- Découplage des composants

### Factory Pattern
- Création d'objets complexes
- Configuration centralisée
- Abstraction de l'instanciation

## Extensibilité

### Plugins
Les plugins peuvent :
- Ajouter de nouvelles routes
- Modifier le comportement existant
- Étendre les modèles
- Ajouter des commandes CLI

### Thèmes
Les thèmes permettent :
- Personnalisation de l'interface
- Override des vues
- Assets personnalisés
- Layouts multiples

### Middlewares (à implémenter)
- Filtrage des requêtes/réponses
- Authentification
- CORS
- Rate limiting

## Sécurité

### Mesures implémentées
- Échappement automatique dans les vues
- Requêtes préparées dans l'ORM
- Validation des entrées
- Protection CSRF (à implémenter)

### À implémenter
- Authentification robuste
- Autorisation basée sur les rôles
- Chiffrement des données sensibles
- Audit des actions

## Performance

### Optimisations actuelles
- Autoloading PSR-4
- Connexions de base de données réutilisables
- Compilation des templates

### Optimisations futures
- Cache de configuration
- Cache de routes
- Cache de vues compilées
- Pool de connexions avancé

## Tests

### Structure de tests
```
tests/
├── Unit/           # Tests unitaires
├── Integration/    # Tests d'intégration
├── Feature/        # Tests fonctionnels
└── fixtures/       # Données de test
```

### Types de tests
- Tests unitaires pour chaque composant
- Tests d'intégration pour les interactions
- Tests fonctionnels pour les scénarios utilisateur
- Tests de performance pour les optimisations

## Déploiement

### Environnements
- **Development** : Debug activé, cache désactivé
- **Staging** : Configuration de production, données de test
- **Production** : Optimisations activées, logs complets

### Configuration
- Variables d'environnement
- Fichiers de configuration par environnement
- Secrets management

## Monitoring

### Logs
- Logs applicatifs structurés
- Logs d'erreurs détaillés
- Logs de performance
- Logs de sécurité

### Métriques
- Temps de réponse
- Utilisation mémoire
- Requêtes base de données
- Erreurs applicatives

