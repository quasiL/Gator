<?php

namespace app\src;

class Request
{
	private array $routeParams = [];
	public function getPath(): false|string
	{
		$separator = explode('/', $_SERVER['REQUEST_URI'] ?? '/');
		$path = '/' . end($separator);
		$position = strpos($path, '?');
		if ($position === false) {
			return $path;
		}
		return substr($path, 0, $position);
	}

	public function getUrl() {
		$path = $_SERVER['REQUEST_URI'];
		$position = strpos($path, '?');
		if ($position !== false) {
			$path = substr($path, 0, $position);
		}
		return $path;
	}

	public function method(): string
	{
		return strtolower($_SERVER['REQUEST_METHOD']);
	}

	public function isGet(): bool
	{
		return $this->method() === 'get';
	}

	public function isPost(): bool
	{
		return $this->method() === 'post';
	}

	public function getBody(): array
	{
		$body = [];
		if ($this->method() === 'get') {
			foreach ($_GET as $key => $value) {
				$body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
			}
		}
		if ($this->method() === 'post') {
			foreach ($_POST as $key => $value) {
				$body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
			}
		}
		return $body;
	}

	public function setRouteParams($params): static
	{
		$this->routeParams = $params;
		return $this;
	}

	public function getRouteParams(): array
	{
		return $this->routeParams;
	}
}