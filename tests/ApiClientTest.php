<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests;

use Siganushka\ApiClient\ApiClient;
use Siganushka\ApiClient\ApiClientInterface;
use Siganushka\ApiClient\Tests\Mock\BarRequest;
use Siganushka\ApiClient\Tests\Mock\FooRequest;
use Siganushka\ApiClient\Tests\Mock\FooResponseMessageExtension;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiClientTest extends BaseTest
{
    public function testAll(): void
    {
        $httpClient = $this->createHttpClient();
        $client = static::createApiClient($httpClient);

        $fooResponse = $client->send(FooRequest::class, ['a' => 'hello']);
        static::assertSame(FooRequest::$responseData, $fooResponse);

        $barResponse = $client->send(BarRequest::class);
        static::assertSame(BarRequest::$responseData, $barResponse);
    }

    public static function createApiClient(HttpClientInterface $httpClient): ApiClientInterface
    {
        $registry = RequestRegistryTest::createRequestRegistry();

        $extensions = [
            new FooResponseMessageExtension($httpClient, $registry),
        ];

        return new ApiClient($httpClient, $registry, $extensions);
    }
}
