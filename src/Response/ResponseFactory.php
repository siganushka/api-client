<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Response;

use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ResponseFactory
{
    /**
     * Creates Mock Response.
     *
     * @param string               $body Mock Response body
     * @param array<string, mixed> $info Mock Response info
     */
    public static function createMockResponse(string $body, array $info = []): ResponseInterface
    {
        $response = new MockResponse($body, $info);
        $response = MockResponse::fromRequest('GET', '/', [], $response);

        return $response;
    }
}
