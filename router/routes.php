<?php

use app\controllers\AuthController;
use app\controllers\MainController;
use app\src\Router;

Router::get('/about', static function () {
	echo 'About page';
});
Router::get('/register', [AuthController::class, 'index']);
Router::post('/register', [AuthController::class, 'register']);

Router::get('/login', [AuthController::class, 'login']);
Router::post('/login', [AuthController::class, 'loginPost']);
Router::get('/logout', [AuthController::class, 'logout']);

Router::get('/contact', [MainController::class, 'index']);
Router::get('/profile', [AuthController::class, 'profile']);