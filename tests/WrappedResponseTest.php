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
        $body = json_encode(FooRequest::$responseData);
        $response = ResponseFactory::createMockResponse($body);
        $parsedResponse = $request->parseResponse($response);

        $wrappedResponse = new WrappedResponse($response, $parsedResponse);
        static::assertInstanceOf(WrappedResponseInterface::class, $wrappedResponse);
        static::assertSame($response->getContent(), $wrappedResponse->getRawResponse()->getContent());
        static::assertSame(FooRequest::$responseData, $wrappedResponse->getParsedResponse());
        static::assertFalse($wrappedResponse->isCached());

        $wrappedResponse = new WrappedResponse($response, $parsedResponse, true);
        static::assertTrue($wrappedResponse->isCached());
    }
}
