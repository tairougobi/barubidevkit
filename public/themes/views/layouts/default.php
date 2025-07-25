<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'MonFramework' ?></title>
    <link rel="stylesheet" href="/themes/default/assets/css/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="container">
                <h1>MonFramework</h1>
                <ul>
                    <li><a href="/">Accueil</a></li>
                    <li><a href="/about">À propos</a></li>
                    <li><a href="/contact">Contact</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="container">
        <?= $content ?? '' ?>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 MonFramework. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="/themes/default/assets/js/app.js"></script>
</body>
</html>

