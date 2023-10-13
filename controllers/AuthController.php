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
use app\src\validation\Validator;

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
		$validator = new Validator();
		$valid = $validator->validate([
			'firstname' => [Validator::RULE_REQUIRED],
			'lastname' => [Validator::RULE_REQUIRED],
			'email' => [Validator::RULE_REQUIRED, Validator::RULE_EMAIL, [Validator::RULE_UNIQUE, 'class' => Validator::class]],
			'password' => [Validator::RULE_REQUIRED, [Validator::RULE_MIN, 'min' => 4], [Validator::RULE_MAX, 'max' => 24]],
			'confirmPassword' => [Validator::RULE_REQUIRED, [Validator::RULE_MATCH, 'match' => 'password']],
		], $user);
		if ($valid) {
			$user->save();
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
		$validator = new Validator();
		$valid = $validator->validate([
			'email' => [Validator::RULE_REQUIRED, Validator::RULE_EMAIL],
			'password' => [Validator::RULE_REQUIRED],
		], $loginForm);
		if ($valid) {
			$user = User::findOne(['email' => $loginForm->email]);
			if (!$user) {
				//$this->addError('email', 'User not found');
				return $this->render('login', ['model' => new LoginForm()]);
			}
			if (!password_verify($loginForm->password, $user->password)) {
				//$this->addError('password', 'Password is incorrect');
				return $this->render('login', ['model' => new LoginForm()]);
			}
			Application::$app->login($user);
			return $this->render('contact', ['name' => 'John Doe', 'age' => 25]);
		}
		return $this->render('login', ['model' => new LoginForm()]);
	}

	public function logout()
	{
		Application::$app->logout();
		return $this->render('login', ['model' => new LoginForm()]);
	}

	public function profile(Request $request)
	{
		return $this->render('profile', ['id' => $request->getRouteParams()['id'], 'username' => $request->getRouteParams()['username']]);
	}
}