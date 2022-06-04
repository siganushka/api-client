<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiClient\Response\ResponseFactory;
use Siganushka\ApiClient\Tests\Mock\BarRequest;
use Siganushka\ApiClient\Tests\Mock\FooRequest;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class BaseTest extends TestCase
{
    public function createHttpClient(): HttpClientInterface
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('request')->willReturnCallback(function (string $method, string $url) {
            return ResponseFactory::createMockResponseWithJson($this->createResponseData($method, $url));
        });

        return $httpClient;
    }

    /**
     * @return array<string, mixed>
     */
    protected function createResponseData(string $method, string $url): array
    {
        $responseDataMapping = [
            '/foo' => FooRequest::$responseData,
            '/bar' => BarRequest::$responseData,
        ];

        return $responseDataMapping[$url] ?? [];
    }
}
