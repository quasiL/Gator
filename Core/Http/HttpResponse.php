<?php

namespace Gator\Core\Http;

use GuzzleHttp\Psr7\Response;

class HttpResponse
{
    private Response $response;

    public function __construct(int $status = 201, array $headers = [], $body = null, string $version = '1.1', string $reason = null)
    {
        $this->response = new Response($status, $headers, $body, $version, $reason);
    }

    public function send(): void
    {
        http_response_code($this->response->getStatusCode());
    }

    public function withStatus(int $int, string $string): void
    {
        $this->response = $this->response->withStatus($int, $string);
    }
}


