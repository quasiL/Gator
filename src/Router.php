<?php

namespace app\src;

use app\src\exception\NotFoundException;
use app\src\middlewares\AuthMiddleware;
use app\src\middlewares\DefaultMiddleware;

class Router
{
	private static array $routes = [];
	private array $registeredMiddlewares = [];
	private Request $request;
	public Response $response;

	public function __construct()
	{
		$this->request = new Request();
		$this->response = new Response();
	}

	public static function get($path, $callback)
	{
		self::$routes['get'][$path] = $callback;
	}

	public static function post($path, $callback): void
	{
		self::$routes['post'][$path] = $callback;
	}

	public function resolve()
	{
		$path =$this->request->getPath();
		$method = $this->request->method();
		$callback = self::$routes[$method][$path] ?? false;

		if (!$callback) {
			throw new NotFoundException();
		}
		if (is_string($callback)) {
			$this->renderView($callback);
			exit();
		}
		if (is_array($callback)) {
			/** @var Controller $controller */
			$controller = new $callback[0];
			$callback[0] = $controller;
			Application::$app->controller = $controller;

			if (!empty($controller->getMiddlewares())) {
				$class = $controller->getMiddlewareClass($controller->getMiddlewares()[0]);
				$middlewareClass = new $class;
				foreach (array_slice($controller->getMiddlewares(), 1) as $middleware) {
					$class = $controller->getMiddlewareClass($middleware);
					$middlewareClass->linkWith(new $class);
				}
				$controller->setMiddleware($middlewareClass);

				$chain = static fn() => Application::$app->controller->getMiddleware()?->check() ?? false;
				do {
					$endOfChain = $chain();
				} while (!$endOfChain);
			}
		}
		return $callback($this->request);
	}

	public function renderView($view, array $params = []): string
	{
		extract($params, EXTR_SKIP);
		ob_start();
		include Application::$ROOT_DIR . "views/$view.php";
		$content = ob_get_clean();

		$this->response->setResponseContent($content);
		$this->response->sendResponse();
		return $content;
	}
}