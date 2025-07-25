<?php

namespace Core\Theme;

class ThemeManager
{
    private string $activeTheme = 'default';
    private array $themes = [];

    public function __construct()
    {
        $this->loadThemes();
    }

    private function loadThemes(): void
    {
        $themeDirs = glob(BASE_PATH . '/themes/*', GLOB_ONLYDIR);

        foreach ($themeDirs as $themeDir) {
            $themeName = basename($themeDir);
            $themeConfigFile = $themeDir .'/theme.json';

            if (file_exists($themeConfigFile)) {
                $config = json_decode(file_get_contents($themeConfigFile), true);
                $this->themes[$themeName] = $config;
            }
        }
    }

    public function setActiveTheme(string $themeName): bool
    {
        if (isset($this->themes[$themeName])) {
            $this->activeTheme = $themeName;
            return true;
        }
        return false;
    }

    public function getActiveTheme(): string
    {
        return $this->activeTheme;
    }

    public function getThemePath(string $themeName = null): string
    {
        $themeName = $themeName ?? $this->activeTheme;
        return BASE_PATH .'/public/themes/' . $themeName;
    }

    public function getThemes(): array
    {
        return $this->themes;
    }

    public function getThemeConfig(string $themeName = null): ?array
    {
        $themeName = $themeName ?? $this->activeTheme;
        return $this->themes[$themeName] ?? null;
    }

    
}

