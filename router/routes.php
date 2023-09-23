<?php

use app\controllers\AuthController;
use app\controllers\MainController;
use app\src\middlewares\AuthMiddleware;
use app\src\Router;

//Router::get('/home', 'home');
Router::get('/about', static function () {
//	$user = \app\src\DB::table('users')
//		->select(['id', 'firstname'])
//		->getAll();
//	var_dump($user);
//	var_dump($users);
//	$users = \app\src\DB::table('people')
//		->id()
//		->string('firstname')->notNull()
//		->int('age')
//		->string('lastname')->notNull()
//		->timestamp('created_at')->default('CURRENT_TIMESTAMP')
//		->create();
//	\app\src\DB::table('people')
//		->insert(['firstname' => 'John2', 'lastname' => 'Doe2', 'age' => 26]);
//	\app\src\DB::table('people')->dropColumn('occupation');
//	$users = \app\src\DB::table('people')->drop();

});
Router::get('/register', [AuthController::class, 'index']);
Router::post('/register', [AuthController::class, 'register']);

Router::get('/login', [AuthController::class, 'login']);
Router::post('/login', [AuthController::class, 'loginPost']);
Router::get('/logout', [AuthController::class, 'logout']);

Router::get('/contact', [MainController::class, 'index']);
Router::get('/profile', [AuthController::class, 'profile']);