<?php

use app\src\Application;

$rootPath = __DIR__;
require_once $rootPath . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable($rootPath);
$dotenv->load();

$config = [
	'userClass' => app\models\User::class,
	'db' => [
		'dsn' => $_ENV['DB_DSN'],
		'user' => $_ENV['DB_USER'],
		'password' => $_ENV['DB_PASSWORD']
	]
];
$app = new Application($rootPath, $config);

$app->migration->applyMigrations();