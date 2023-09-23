<?php

namespace app\src;

class Migration
{
	public function applyMigrations(): void
	{
		$this->createMigrationsTable();
		$appliedMigrations = $this->getAppliedMigrations();
		$newMigrations = [];

		$files = scandir(Application::$ROOT_DIR . '/migrations');
		$toApplyMigrations = array_diff($files, $appliedMigrations);
		foreach ($toApplyMigrations as $migration) {
			if ($migration === '.' || $migration === '..') {
				continue;
			}
			require_once Application::$ROOT_DIR . '/migrations/' . $migration;
			$className = pathinfo($migration, PATHINFO_FILENAME);
			$instance = new $className;
			echo 'Applying migration ' . $migration . PHP_EOL;
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

	private function createMigrationsTable(): void
	{
		DB::table('migrations')
			->id()
			->string('migration')->notNull()
			->timestamp('created_at')->default('CURRENT_TIMESTAMP')->notNull()
			->create();
	}

	private function getAppliedMigrations()
	{
		return DB::table('migrations')
			->select(['migration'])
			->getAll(\PDO::FETCH_COLUMN);
	}

	private function saveMigrations(array $migrations): void
	{
		foreach ($migrations as $migration) {
			DB::table('migrations')->insert(['migration' => $migration]);
		}
	}
}