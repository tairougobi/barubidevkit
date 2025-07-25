<?php

namespace Core\Console\Commands;

use Core\Console\Command;

class MakeMigrationCommand extends Command
{
    protected string $name = 'make:migration';
    protected string $description = 'Créer une nouvelle migration';

    public function handle(array $args = []): int
    {
        if (empty($args[0])) {
            $this->error('Nom de la migration requis');
            return 1;
        }

        $migrationName = $args[0];
        $timestamp = date('Y_m_d_His');
        $fileName = $timestamp . '_' . $migrationName . '.php';
        $migrationPath = BASE_PATH . '/database/migrations/' . $fileName;

        // Créer le dossier s'il n'existe pas
        $dir = dirname($migrationPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $template = $this->getMigrationTemplate($migrationName);
        file_put_contents($migrationPath, $template);

        $this->info("Migration {$fileName} créée avec succès");
        return 0;
    }

    private function getMigrationTemplate(string $name): string
    {
        $className = $this->studlyCase($name);
        
        return "<?php

use Core\Database\Migration;
use Core\Database\Schema;

class {$className} extends Migration
{
    public function up(): void
    {
        \$this->createTable('table_name', function (Schema \$table) {
            \$table->id();
            \$table->string('name');
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        \$this->dropTable('table_name');
    }
}
";
    }

    private function studlyCase(string $value): string
    {
        return str_replace(' ', '', ucwords(str_replace(['_', '-'], ' ', $value)));
    }
}

