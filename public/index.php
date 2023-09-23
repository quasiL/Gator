<?php

use app\src\Application;

$path = $_SERVER['DOCUMENT_ROOT'] . '/' . array_slice(explode('/', __DIR__), -2, 2)[0] . '/';
// check difference between $_SERVER['DOCUMENT_ROOT'] and __DIR__ and put it to the app
require_once $path . '/vendor/autoload.php';

$app = new Application($path);

require_once $path . '/router/routes.php';

$app->run();