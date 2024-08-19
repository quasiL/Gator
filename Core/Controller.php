<?php

namespace Gator\Core;

abstract class Controller
{
    public function render(string $view, array $data = []): void
    {
        extract($data);
        ob_start();
        require Application::$rootPath . '/views/' . $view . '.php';
        $output = ob_get_clean();
        echo $output;
    }
}