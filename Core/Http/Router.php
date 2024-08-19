<?php

namespace Gator\Core\Http;

use Gator\Core\Application;
use Gator\Core\Attributes\Route;
use ReflectionClass;

class Router
{
    private HttpRequest $request;
    private HttpResponse $response;
    private string $controllersPath;

    public function __construct()
    {
        $this->request = new HttpRequest();
        $this->response = new HttpResponse();
        $this->controllersPath = Application::$rootPath . '/Backend/Controllers';
    }

    public function dispatch(): void
    {
        $queryParams = $this->request->getQueryParams();
        $requestUri = '/';
        empty($queryParams) ?: $requestUri .= $queryParams['url'];
        $requestMethod = $this->request->getMethod();

        $controller = $this->resolveController($requestUri, $requestMethod);

        if ($controller === null) {
            $this->response->withStatus(404, 'Not Found');
            $this->response->send();
            echo 'Not Found';
            return;
        }

        [$controllerInstance, $method] = $controller;

        if (is_callable([$controllerInstance, $method])) {
            $controllerInstance->$method($this->request, $this->response);
        } else {
            $this->response->withStatus(500, 'Internal Server Error');
            $this->response->send();
        }
    }

    /**
     * Resolves the controller based on the request URI and request method.
     *
     * @param string $requestUri The request URI.
     * @param string $requestMethod The request method.
     * @return array<callable>|null An array containing the controller instance and the method name,
     * or null if no matching route is found.
     */
    private function resolveController(string $requestUri, string $requestMethod): array|null
    {
        $files = glob($this->controllersPath . '/*.php');

        foreach ($files as $file) {
            include_once $file;

            $className = basename($file, '.php');
            $fullClassName = 'Gator\\Backend\\Controllers\\' . $className;

            if (class_exists($fullClassName)) {
                $reflection = new ReflectionClass($fullClassName);

                foreach ($reflection->getMethods() as $method) {
                    $attributes = $method->getAttributes(Route::class);
                    foreach ($attributes as $attribute) {
                        $route = $attribute->newInstance();

                        if ($route->getPath() === $requestUri && $route->getMethod() === $requestMethod) {
                            return [new $fullClassName(), $method->getName()];
                        }
                    }
                }
            }
        }

        return null;
    }
}
