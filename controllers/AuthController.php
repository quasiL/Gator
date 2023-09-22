<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\User;
use app\src\Application;
use app\src\Controller;
use app\src\middlewares\AuthMiddleware;
use app\src\middlewares\BaseMiddleware;
use app\src\Request;
use app\src\Response;

class AuthController extends Controller
{
	public function index(Request $request)
	{
		$user = new User();
		return $this->render('home', ['model' => $user]);
	}
	public function register(Request $request)
	{
		$user = new User();
		$user->loadData($request->getBody());
		if ($user->validate() && $user->save()) {
			//return $this->render('home', ['model' => $user]);
			Application::$app->session->setFlash('success', 'You have successfully registered!');
			//Application::$app->router->response->redirect('/');
		}
		return $this->render('home', ['model' => new User()]);
	}

	public function login(Request $request)
	{
		return $this->render('login', ['model' => new LoginForm()]);
	}

	public function loginPost(Request $request)
	{
		$loginForm = new LoginForm();
		$loginForm->loadData($request->getBody());
		if ($loginForm->validate() && $loginForm->login()) {
			return $this->render('contact', ['name' => 'John Doe', 'age' => 25]);
		}
		return $this->render('login', ['model' => new LoginForm()]);
	}

	public function logout()
	{
		Application::$app->logout();
		return $this->render('login', ['model' => new LoginForm()]);
	}

	public function profile()
	{
		return $this->render('profile');
	}
}