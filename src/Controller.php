<?php

namespace app\src;

use app\src\middlewares\AuthMiddleware;
use app\src\middlewares\BaseMiddleware;
use app\src\middlewares\DefaultMiddleware;

abstract class Controller
{
	protected array $middlewares = [];
	private ?BaseMiddleware $middleware = null;
	public function render($view, array $params = []): string
	{
		return Application::$app->router->renderView($view, $params);
	}

	public function setMiddleware(BaseMiddleware $middleware): void
	{
		$this->middleware = $middleware;
	}

	public function getMiddleware(): ?BaseMiddleware
	{
		return $this->middleware;
	}

	public function getMiddlewares(): array
	{
		return $this->middlewares;
	}

	public function getMiddlewareClass($action): ?string
	{
		return match ($action) {
			'auth' => AuthMiddleware::class,
			'default' => DefaultMiddleware::class,
			default => null,
		};
	}
}