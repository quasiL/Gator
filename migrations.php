<?php

use app\src\Application;

$rootPath = __DIR__;
require_once $rootPath . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable($rootPath);
$dotenv->load();

$config = [
	'db' => [
		'dsn' => $_ENV['DB_DSN'],
		'user' => $_ENV['DB_USER'],
		'password' => $_ENV['DB_PASSWORD']
	]
];
$app = new Application($rootPath, $config);

if (isset($argc, $argv[1])) {
	for ($i = 1; $i < $argc; $i++) {
		$app->migration->dropMigration($argv[$i]);
	}
} else {
	$app->migration->applyMigrations();
}