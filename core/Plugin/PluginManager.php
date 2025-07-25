<?php

namespace Core\Plugin;

class PluginManager
{
    private static ?PluginManager $instance = null;
    private array $plugins = [];

    private function __construct()
    {
        $this->loadPlugins();
    }

    public static function getInstance(): PluginManager
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function loadPlugins(): void
    {
        $pluginDirs = glob(MODULES_PATH . '/*', GLOB_ONLYDIR);

        foreach ($pluginDirs as $pluginDir) {
            $pluginName = basename($pluginDir);
            $pluginConfigFile = $pluginDir . '/plugin.php';

            if (file_exists($pluginConfigFile)) {
                $config = require $pluginConfigFile;
                $this->plugins[$pluginName] = $config;

                // Enregistrer l'autoloader pour les classes du plugin
                if (isset($config['autoload'])) {
                    foreach ($config['autoload'] as $namespace => $path) {
                        spl_autoload_register(function ($class) use ($namespace, $pluginDir, $path) {
                            if (strpos($class, $namespace) === 0) {
                                $relativePath = str_replace($namespace, '', $class);
                                $relativePath = str_replace('\\', '/', $relativePath);
                                $file = $pluginDir . '/' . $path . $relativePath . '.php';
                                if (file_exists($file)) {
                                    require_once $file;
                                }
                            }
                        });
                    }
                }

                // Exécuter le fichier de démarrage du plugin si défini
                if (isset($config['bootstrap']) && file_exists($pluginDir . '/' . $config['bootstrap'])) {
                    require_once $pluginDir . '/' . $config['bootstrap'];
                }
            }
        }
    }

    public function getPlugins(): array
    {
        return $this->plugins;
    }

    public function getPlugin(string $name): ?array
    {
        return $this->plugins[$name] ?? null;
    }

    public function fireEvent(string $eventName, ...$args)
    {
        $results = [];
        foreach ($this->plugins as $pluginName => $config) {
            if (isset($config['events'][$eventName])) {
                foreach ($config['events'][$eventName] as $listener) {
                    if (is_callable($listener)) {
                        $results[$pluginName][] = $listener(...$args);
                    } elseif (is_string($listener) && strpos($listener, '@') !== false) {
                        [$class, $method] = explode('@', $listener);
                        if (class_exists($class) && method_exists($class, $method)) {
                            $instance = new $class();
                            $results[$pluginName][] = $instance->$method(...$args);
                        }
                    }
                }
            }
        }
        return $results;
    }
}

