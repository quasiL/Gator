<?php

namespace app\src;

class Response
{
	private string $responseContent;

	public function __construct()
	{
		$this->responseContent = '';
	}

	public function setResponseContent(string $content): void
	{
		$this->responseContent = $content;
	}
	public function sendResponse() : void
	{
		header('Content-Type: text/html; charset=utf-8');
		http_response_code(200);
		echo $this->responseContent;
	}
	public function setStatusCode(int $code): void
	{
		http_response_code($code);
	}

	public function redirect(string $url)
	{
		header('Location: ' . $url);
	}
}