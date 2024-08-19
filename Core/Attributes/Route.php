<?php

namespace Gator\Core\Attributes;

use Attribute;

/**
 * Attribute used to define a route in controllers
 *
 * @package Gator\Core
 */
#[Attribute]
final class Route
{
    private string $path;
    private string $method;

    public function __construct(string $path, string $method)
    {
        $this->path = $path;
        $this->method = $method;
    }

    /**
     * Retrieves the path of the route.
     *
     * @return string The path of the route.
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Retrieves the method of the route.
     *
     * @return string The method of the route.
     */
    public function getMethod(): string
    {
        return $this->method;
    }
}
