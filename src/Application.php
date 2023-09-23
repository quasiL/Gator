<?php

namespace app\src;

use app\models\User;
use Dotenv\Dotenv;

class Application
{
	public Router $router;
	public static string $ROOT_DIR;
	public static Application $app;
	public Migration $migration;
	public Database $db;
	public Session $session;
	public ?Model $user;
	public Controller $controller;
	public function __construct(string $rootPath, bool $migration = false)
	{
		$dotenv = Dotenv::createImmutable($rootPath);
		$dotenv->load();
		$config = [
			'userClass' => User::class,
			'db' => [
				'dsn' => $_ENV['DB_TYPE'] . ':host=' . $_ENV['DB_HOST'] . ';port=' . $_ENV['DB_PORT'] . ';dbname='
					. $_ENV['DB_NAME'],
				'user' => $_ENV['DB_USER'],
				'password' => $_ENV['DB_PASSWORD']
			]
		];

		self::$app = $this;
		self::$ROOT_DIR = $rootPath;
		$this->router = new Router();
		!$migration ?: $this->migration = new Migration();
		$this->db = new Database($config['db']);
		$this->session = new Session();

		$primaryValue = $this->session->get('user');
		$userClass = $config['userClass'];
		if ($primaryValue) {
			$primaryKey = $userClass::primaryKey();
			$this->user = $userClass::findOne([$primaryKey => $primaryValue]);
		} else {
			$this->user = null;
		}
	}

	public static function isGuest(): bool
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

	public function login(Model $user)
	{
		$this->user = $user;
		$primaryKey = $user->primaryKey();
		$primaryValue = $user->{$primaryKey};
		$this->session->set('user', $primaryValue);
		return true;
	}

	public function logout(): void
	{
		$this->user = null;
		$this->session->remove('user');
	}
}