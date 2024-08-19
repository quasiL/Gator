<?php

namespace Gator\Core;

use Gator\Core\Database\Migration;
use Gator\Core\Http\Router;

class Application
{
    public static string $rootPath;
    private Router $router;

    public function __construct(string $rootPath, bool $isCli = false)
    {
        self::$rootPath = $rootPath;
        if (!$isCli) {
            $this->router = new Router();
        }
    }

    public function run(): void
    {
        $this->router->dispatch();
    }
}
