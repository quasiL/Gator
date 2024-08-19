<?php

namespace Gator\Core;

abstract class Controller
{
    /**
     * Renders a view
     *
     * @param string $view The name of the view
     * @param array $data The data to be passed to the view
     * @return void
     */
    public final function render(string $view, array $data = []): void
    {
        extract($data);
        ob_start();
        require Application::$rootPath . '/views/' . $view . '.php';
        $output = ob_get_clean();
        echo $output;
    }
}