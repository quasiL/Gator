<?php

namespace Gator\Core\Database;

use Gator\Core\Application;
use PDO;

class Migration
{
    public function applyMigrations(): void
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();
        $newMigrations = [];

        $files = $this->getMigrationFiles();
        $toApplyMigrations = array_diff($files, $appliedMigrations);

        foreach ($toApplyMigrations as $migration) {
            require_once Application::$rootPath . '/migrations/' . $migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className;
            $instance->up();
            echo 'Applied migration ' . $migration . PHP_EOL;
            $newMigrations[] = $migration;
        }

        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else {
            echo 'All migrations are applied' . PHP_EOL;
        }
    }

    public function revertMigration(): void
    {
        $files = $this->getMigrationFiles();
        $lastMigration = end($files);

        require_once Application::$rootPath . '/migrations/' . $lastMigration;
        $className = pathinfo($lastMigration, PATHINFO_FILENAME);
        $instance = new $className;
        $instance->down();
        echo 'Reverted migration ' . $lastMigration . PHP_EOL;
        $this->removeMigration($lastMigration);
    }

    private function createMigrationsTable(): void
    {
        Burt::table('migrations')
            ->id()
            ->string('migration')->notNull()
            ->timestamp('created_at')->default('CURRENT_TIMESTAMP')->notNull()
            ->create();
    }

    private function getAppliedMigrations()
    {
        return Burt::table('migrations')
            ->select(['migration'])
            ->getAll(PDO::FETCH_COLUMN);
    }

    private function saveMigrations(array $migrations): void
    {
        foreach ($migrations as $migration) {
            Burt::table('migrations')->insert(['migration' => $migration]);
        }
    }

    private function removeMigration(string $migration): void
    {
        Burt::table('migrations')
            ->where('migration', '=', $migration)
            ->delete();
    }

    private function getMigrationFiles(): array
    {
        $files = scandir(Application::$rootPath . '/migrations');

        $files = array_filter($files, function($file) {
            return $file !== '.' && $file !== '..';
        });

        sort($files);
        return $files;
    }
}