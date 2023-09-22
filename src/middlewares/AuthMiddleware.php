<?php

namespace app\src\middlewares;

use app\src\Application;
use app\src\exception\ForbiddenException;

class AuthMiddleware extends BaseMiddleware
{
	public string $action = 'auth';

	public function check(): bool
	{
		if (Application::isGuest()) {
			throw new ForbiddenException();
		}
		return parent::check();
	}
}