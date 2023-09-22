<?php

namespace app\src\middlewares;

class DefaultMiddleware extends BaseMiddleware
{
	public string $action = 'default';
	public function check(): bool
	{
		var_dump("DefaultMiddleware check");
		return parent::check();
	}
}