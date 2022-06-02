<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiClient\RequestClient;
use Siganushka\ApiClient\RequestClientInterface;
use Siganushka\ApiClient\Tests\Mock\BarRequest;
use Siganushka\ApiClient\Tests\Mock\FooRequest;
use Siganushka\ApiClient\Tests\Mock\FooResponseMessageExtension;

class RequestClientTest extends TestCase
{
    public function testAll(): void
    {
        $client = static::createRequestClient();

        $fooResponse = $client->send(FooRequest::class, ['a' => 'hello']);
        static::assertSame(FooRequest::$responseData, $fooResponse);

        $barResponse = $client->send(BarRequest::class);
        static::assertSame(BarRequest::$responseData, $barResponse);
    }

    public static function createRequestClient(): RequestClientInterface
    {
        $registry = RequestRegistryTest::createRequestRegistry();

        $extensions = [
            new FooResponseMessageExtension($registry),
        ];

        return new RequestClient($registry, $extensions);
    }
}
