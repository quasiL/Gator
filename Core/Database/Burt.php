<?php

namespace Gator\Core\Database;

use Dotenv\Dotenv;
use Gator\Core\Application;

/**
 * @method static table(string $string)
 */
class Burt
{
    private static ?DBHandler $instance = null;

    private static function getInstance(): DBHandler
    {
        if (!self::$instance) {
            $dotenv = Dotenv::createImmutable(Application::$rootPath);
            $dotenv->load();

            self::$instance = new DBHandler([
                'dsn' => $_ENV['DB_CONNECTION'] . ':host=' . $_ENV['DB_HOST'] . ';port=' . $_ENV['DB_PORT'] .
                    ';dbname=' . $_ENV['DB_DATABASE'],
                'user' => $_ENV['DB_USERNAME'],
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