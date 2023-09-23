<?php

use app\src\Application;

$rootPath = __DIR__;
require_once $rootPath . '/vendor/autoload.php';

$app = new Application($rootPath, true);

$app->migration->applyMigrations();