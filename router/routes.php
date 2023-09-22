<?php

use app\controllers\AuthController;
use app\controllers\MainController;
use app\src\middlewares\AuthMiddleware;
use app\src\Router;

//Router::get('/home', 'home');
Router::get('/about', static function () {
	$db = \app\src\Application::$app->db;
	$users = \app\src\DB::table('users')
		->select(['id', 'firstname'])
		->where('firstname', '=', 'Artur')
		->orWhere('firstname', '=', 'John')
		->getFirst();
	var_dump($users);
});
Router::get('/register', [AuthController::class, 'index']);
Router::post('/register', [AuthController::class, 'register']);

Router::get('/login', [AuthController::class, 'login']);
Router::post('/login', [AuthController::class, 'loginPost']);
Router::get('/logout', [AuthController::class, 'logout']);

Router::get('/contact', [MainController::class, 'index']);
Router::get('/profile', [AuthController::class, 'profile']);