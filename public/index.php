<?php

use app\src\Application;

$path = $_SERVER['DOCUMENT_ROOT'] . '/' . array_slice(explode('/', __DIR__), -2, 2)[0] . '/';
// check difference between $_SERVER['DOCUMENT_ROOT'] and __DIR__ and put it to the app
require_once $path . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable($path);
$dotenv->load();

$config = [
	'userClass' => app\models\User::class,
	'db' => [
		'dsn' => $_ENV['DB_DSN'],
		'user' => $_ENV['DB_USER'],
		'password' => $_ENV['DB_PASSWORD']
	]
];
$app = new Application($path, $config);

require_once $path . '/router/routes.php';

$app->run();