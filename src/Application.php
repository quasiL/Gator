<?php

namespace app\src;

class Application
{
	public Router $router;
	public static string $ROOT_DIR;
	public static Application $app;
	public Migration $migration;
	public Database $db;
	public Session $session;
	public ?DbModel $user;
	public string $userClass;
	public Controller $controller;
	public function __construct(string $rootPath, array $config)
	{
		$this->userClass = $config['userClass'];
		self::$app = $this;
		self::$ROOT_DIR = $rootPath;
		$this->router = new Router();
		$this->migration = new Migration($config['db']);
		$this->db = new Database($config['db']);
		$this->session = new Session();

		$primaryValue = $this->session->get('user');
		if ($primaryValue) {
			$primaryKey = $this->userClass::primaryKey();
			$this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
		} else {
			$this->user = null;
		}
	}

	public static function isGuest()
	{
		return !self::$app->user;
	}

	public function run(): void
	{
		try {
			$this->router->resolve();
		} catch (\Exception $e) {
			$this->router->response->setStatusCode($e->getCode());
			$this->router->renderView('_error', ['exception' => $e]);
		}
	}

	public function login(DbModel $user)
	{
		$this->user = $user;
		$primaryKey = $user->primaryKey();
		$primaryValue = $user->{$primaryKey};
		$this->session->set('user', $primaryValue);
		return true;
	}

	public function logout()
	{
		$this->user = null;
		$this->session->remove('user');
	}
}