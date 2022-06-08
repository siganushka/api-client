<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests;

use Siganushka\ApiClient\RequestRegistry;
use Siganushka\ApiClient\RequestRegistryInterface;
use Siganushka\ApiClient\Tests\Mock\BarRequest;
use Siganushka\ApiClient\Tests\Mock\FooRequest;
use Siganushka\ApiClient\Tests\Mock\FooRequestOverrideResponse;
use Siganushka\Contracts\Registry\Exception\ServiceNonExistingException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RequestRegistryTest extends BaseTest
{
    public function testAll(): void
    {
        $httpClient = $this->createHttpClient();
        $registry = static::createRequestRegistry($httpClient);

        static::assertInstanceOf(FooRequest::class, $registry->get(FooRequest::class));
        static::assertInstanceOf(BarRequest::class, $registry->get(BarRequest::class));
    }

    public function testNonExistingException(): void
    {
        $this->expectException(ServiceNonExistingException::class);
        $this->expectExceptionMessage('Service "stdClass" for "Siganushka\ApiClient\RequestRegistry" does not exist');

        $httpClient = $this->createHttpClient();
        $registry = static::createRequestRegistry($httpClient);
        $registry->get(\stdClass::class);
    }

    public static function createRequestRegistry(HttpClientInterface $httpClient): RequestRegistryInterface
    {
        $requests = [
            new FooRequest(),
            new FooRequestOverrideResponse(),
            new BarRequest(),
        ];

        return new RequestRegistry($httpClient, $requests);
    }
}
