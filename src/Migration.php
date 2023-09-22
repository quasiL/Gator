<?php

namespace app\src;

class Migration
{
	public \PDO $pdo;

	public function __construct(array $config)
	{
		$dsn = $config['dsn'] ?? '';
		$user = $config['user'] ?? '';
		$password = $config['password'] ?? '';
		$this->pdo = new \PDO($dsn, $user, $password);
		$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

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
			$this->log('All migrations are applied');
		}
	}

	public function dropMigration($migration)
	{
		require_once Application::$ROOT_DIR . '/migrations/' . $migration;
		$className = pathinfo($migration, PATHINFO_FILENAME);
		$instance = new $className;
		echo 'Dropping migration ' . $migration . PHP_EOL;
		$instance->down();
		echo 'Dropped migration ' . $migration . PHP_EOL;
	}

	public function createMigrationsTable(): void
	{
		$this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
    		id INT AUTO_INCREMENT PRIMARY KEY,
    		migration VARCHAR(255),
			created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    	) ENGINE=INNODB;");
	}

	protected function getAppliedMigrations()
	{
		$statement = $this->pdo->prepare("SELECT migration FROM migrations");
		$statement->execute();

		return $statement->fetchAll(\PDO::FETCH_COLUMN);
	}

	protected function saveMigrations(array $migrations)
	{
		$str = implode(',', array_map(fn($m) => "('$m')", $migrations));
		$statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $str");
		$statement->execute();
	}

	public function prepare($sql)
	{
		return $this->pdo->prepare($sql);
	}

	protected function log($message)
	{
		echo '[' . date('Y-m-d H:i:s') . '] - ' . $message . PHP_EOL;
	}
}