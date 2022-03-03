<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiClient\Response\ResponseFactory;
use Siganushka\ApiClient\Tests\Mock\FooRequest;
use Siganushka\ApiClient\WrappedResponse;
use Siganushka\ApiClient\WrappedResponseInterface;

class WrappedResponseTest extends TestCase
{
    public function testAll(): void
    {
        $request = new FooRequest();

        /** @var string */
        $body = json_encode(['message' => 'hello']);
        $response = ResponseFactory::createMockResponse($body);

        $wrappedResponse = new WrappedResponse($request, $response);
        static::assertInstanceOf(WrappedResponseInterface::class, $wrappedResponse);
        static::assertSame($response, $wrappedResponse->getRawResponse());
        static::assertSame($body, $wrappedResponse->getParsedBody());
    }
}
