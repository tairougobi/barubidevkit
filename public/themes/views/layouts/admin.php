<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - MonFramework</title>
    <link rel="stylesheet" href="/themes/default/assets/css/style.css">
    <style>
        .admin-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            height: 100vh;
            background: #343a40;
            color: white;
            padding: 1rem;
        }
        .admin-content {
            margin-left: 250px;
            padding: 2rem;
        }
        .admin-nav a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 0.5rem 0;
            border-bottom: 1px solid #495057;
        }
        .admin-nav a:hover {
            background: #495057;
            padding-left: 1rem;
        }
    </style>
</head>
<body>
    <div class="admin-sidebar">
        <h2>Administration</h2>
        <nav class="admin-nav">
            <a href="/admin">Tableau de bord</a>
            <a href="/admin/users">Utilisateurs</a>
            <a href="/admin/content">Contenu</a>
            <a href="/admin/plugins">Plugins</a>
            <a href="/admin/themes">Thèmes</a>
            <a href="/admin/settings">Paramètres</a>
        </nav>
    </div>

    <div class="admin-content">
        <header>
            <h1>Administration</h1>
        </header>
        
        <main>
            <?= $content ?? '' ?>
        </main>
    </div>

    <script src="/themes/default/assets/js/app.js"></script>
</body>
</html>

