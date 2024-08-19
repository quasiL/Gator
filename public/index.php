<?php

use Gator\Core\Application;

error_reporting(E_ALL);
ini_set('display_errors', 1);

$rootPath = $_SERVER['DOCUMENT_ROOT'] . '/' .
    array_slice(explode('/', __DIR__), -2, 2)[0] . '/';

require_once $rootPath . '/vendor/autoload.php';

$app = new Application($rootPath);
$app->run();