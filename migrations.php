<?php

use Gator\Core\Application;
use Gator\Core\Database\Migration;

require_once __DIR__ . '/vendor/autoload.php';

$app = new Application(__DIR__, true);
$migration = new Migration();

if ($argc > 1) {
    $command = $argv[1];

    match ($command) {
        'migrate' => $migration->applyMigrations(),
        'rollback' => $migration->revertMigration(),
        default => var_dump('Invalid command')
    };
} else {
    echo "No arguments provided" . PHP_EOL;
}