<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiClient\RequestRegistry;
use Siganushka\ApiClient\RequestRegistryInterface;
use Siganushka\ApiClient\Tests\Mock\BarRequest;
use Siganushka\ApiClient\Tests\Mock\FooRequest;
use Siganushka\Contracts\Registry\Exception\ServiceNonExistingException;

class RequestRegistryTest extends TestCase
{
    public function testAll(): void
    {
        $registry = static::createRequestRegistry();

        $request = $registry->get(FooRequest::class);
        static::assertInstanceOf(FooRequest::class, $request);
    }

    public function testNonExistingException(): void
    {
        $this->expectException(ServiceNonExistingException::class);
        $this->expectExceptionMessage('Service "stdClass" for "Siganushka\ApiClient\RequestRegistry" does not exist');

        $registry = static::createRequestRegistry();
        $registry->get(\stdClass::class);
    }

    public static function createRequestRegistry(): RequestRegistryInterface
    {
        $requests = [
            new FooRequest(),
            new BarRequest(),
        ];

        return new RequestRegistry($requests);
    }
}
