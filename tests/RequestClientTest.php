<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests;

use Siganushka\ApiClient\RequestClient;
use Siganushka\ApiClient\RequestClientInterface;
use Siganushka\ApiClient\Tests\Mock\BarRequest;
use Siganushka\ApiClient\Tests\Mock\FooRequest;
use Siganushka\ApiClient\Tests\Mock\FooResponseMessageExtension;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RequestClientTest extends BaseTest
{
    public function testAll(): void
    {
        $httpClient = $this->createHttpClient();
        $client = static::createRequestClient($httpClient);

        $fooResponse = $client->send(FooRequest::class, ['a' => 'hello']);
        static::assertSame(FooRequest::$responseData, $fooResponse);

        $barResponse = $client->send(BarRequest::class);
        static::assertSame(BarRequest::$responseData, $barResponse);
    }

    public static function createRequestClient(HttpClientInterface $httpClient): RequestClientInterface
    {
        $registry = RequestRegistryTest::createRequestRegistry($httpClient);

        $extensions = [
            new FooResponseMessageExtension($registry),
        ];

        return new RequestClient($registry, $extensions);
    }
}
