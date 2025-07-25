<?php

namespace Core\Console\Commands;

use Core\Console\Command;

class LinkThemesCommand extends Command
{
    protected string $name = 'link:themes';
    protected string $description = 'Crée un lien symbolique vers le dossier /themes dans /public.';
    protected function basePath(string $path = ''): string
    {
        return dirname(__DIR__, 3) . ($path ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : '');
    }

    public function handle(array $args = []): int
    {
        $publicPath = $this->basePath('public');
        $themesPath = $this->basePath('themes');
        $linkPath = $publicPath . DIRECTORY_SEPARATOR . 'themes';

        if (file_exists($linkPath)) {
            $this->comment("🔗 Le lien 'public/themes' existe déjà.");
            return 0;
        }

        if (symlink($themesPath, $linkPath)) {
            $this->info("✅ Lien symbolique créé : public/themes → ../themes");
            return 0;
        } else {
            $this->error("❌ Échec lors de la création du lien symbolique.");
            return 1;
        }
    }

}
