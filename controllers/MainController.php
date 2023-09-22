<?php

namespace app\controllers;

use app\src\Controller;
use app\src\middlewares\AuthMiddleware;
use app\src\Request;

class MainController extends Controller
{
	protected array $middlewares = ['auth', 'default'];
	public function index(): string
	{
		return $this->render('contact', ['name' => 'John Doe', 'age' => 25]);
	}

	public function post(Request $request)
	{
		var_dump($request->getBody()['subject']);
	}
}