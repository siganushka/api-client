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

    /**
     * Creates Mock Response With Json.
     *
     * @param array<string, mixed> $data to json for Response body
     * @param array<string, mixed> $info Mock Response info
     */
    public static function createMockResponseWithJson(array $data, array $info = []): ResponseInterface
    {
        /** @var string */
        $json = json_encode($data);
        $response = static::createMockResponse($json, $info);

        return $response;
    }
}
