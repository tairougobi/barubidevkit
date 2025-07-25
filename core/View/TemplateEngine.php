<?php
namespace Core\View;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Symfony\Component\Yaml\Yaml;
use Core\Theme\ThemeManager;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\TwigFilter;

class TemplateEngine
{
    protected Environment $twig;
    protected ThemeManager $themeManager;

    public function __construct()
    {
        $this->themeManager = new ThemeManager();

        $loader = new FilesystemLoader($this->themeManager->getThemePath() . '/');
        $this->twig = new Environment($loader, [
            'cache' => false,
            'autoescape' => false,
        ]);

        // Ajouter le filtre "theme"
        $filter = new TwigFilter('theme', function ($assetPath) {
            $theme = $this->themeManager->getActiveTheme();
            return "/themes/{$theme}/" . ltrim($assetPath, '/');
        });

        $this->twig->addFilter($filter);
    }

    public function renderPage(string $file): string
    {
        if (!file_exists($file)) {
            throw new \Exception("Fichier page introuvable : {$file}");
        }

        $content = file_get_contents($file);
        [$front, $body] = explode('==', $content, 2);

        $meta = Yaml::parse($front);
        $layout = $meta['layout'] ?? 'default';
        $title = $meta['title'] ?? '';
        $url = $meta['url'] ?? '';

        try {
            return $this->twig->render("layouts/{$layout}.htm", [
                'page_content' => $body,
                'title' => $title,
                'url' => $url,
                'theme' => $this->themeManager->getActiveTheme(),
            ]);
        } catch (LoaderError $e) {
            // Template non trouvé
            return "Erreur : template '{$layout}.htm' introuvable dans le thème.";
        } catch (RuntimeError $e) {
            // Erreur lors de l’exécution du template
            return "Erreur d’exécution dans le template : " . $e->getMessage();
        } catch (SyntaxError $e) {
            // Erreur de syntaxe Twig
            return "Erreur de syntaxe dans le template : " . $e->getMessage();
        } catch (\Exception $e) {
            // Autres erreurs générales
            return "Erreur inattendue : " . $e->getMessage();
        }
    }
}
