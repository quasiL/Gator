<?php

namespace Gator\Core\Http;

use GuzzleHttp\Psr7\Stream;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Uri;

class HttpRequest
{
    private const DEFAULT_SERVER_PROTOCOL = 'HTTP/1.1';
    private ServerRequest $request;

    public function __construct()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $uri = new Uri($_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $uri);

        $body = new Stream(fopen('php://temp', 'r+'));

        $protocol = $_SERVER['SERVER_PROTOCOL'] ?? self::DEFAULT_SERVER_PROTOCOL;
        $version = substr($protocol, strpos($protocol, '/') + 1);

        $this->request = new ServerRequest(
            $_SERVER['REQUEST_METHOD'],
            $uri,
            [],
            $body,
            $version
        );
    }

    public function getMethod(): string
    {
        return $this->request->getMethod();
    }

    public function getUri(): string
    {
        return $this->request->getUri();
    }

    public function getProtocolVersion(): string
    {
        return $this->request->getProtocolVersion();
    }

    /**
     * Returns an associative array of query parameters.
     *
     * @return array<string, string> The query parameters.
     */
    public function getQueryParams(): array
    {
        $globals = $this->request::fromGlobals();
        return $globals->getQueryParams();
    }

    /**
     * Retrieves the body of the HTTP request.
     *
     * This method reads the input from the request body and parses it based on the content type.
     *
     * @return array|string|null The parsed body of the request, or null if the content type is not supported.
     */
    public function getBody(): array|string|null
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        $input = file_get_contents('php://input');

        $body = match (true) {
            str_contains($contentType, 'application/json') => json_decode($input, true),
            str_contains($contentType, 'text/plain') => $input,
            str_contains($contentType, 'text/html') => $input,
            default => null
        };

        if (str_contains($contentType, 'application/x-www-form-urlencoded')) {
            parse_str($input, $parsed);
            $body = $parsed;
        }

        return $body;
    }
}