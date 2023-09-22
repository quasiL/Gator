<?php

namespace app\src;

/**
 * @method static table(string $string)
 */
class DB
{
	private static ?Database $instance = null;

	public static function getInstance(): Database
	{
		if (!self::$instance) {
			self::$instance = new Database([
				'dsn' => $_ENV['DB_DSN'],
				'user' => $_ENV['DB_USER'],
				'password' => $_ENV['DB_PASSWORD']
			]);
		}
		return self::$instance;
	}

	public static function __callStatic($method, $args)
	{
		$instance = self::getInstance();
		return call_user_func_array([$instance, $method], $args);
	}
}