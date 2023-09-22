<?php

namespace app\src\middlewares;

abstract class BaseMiddleware
{
	public string $action = '';
	private ?BaseMiddleware $next = null;
	public function check(): bool
	{
		if (!$this->next) {
			return true;
		}
		return $this->next->check();
	}

	public function linkWith(BaseMiddleware $next): BaseMiddleware
	{
		$this->next = $next;
		return $next;
	}
}