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

        $data = ['message' => 'hello'];

        /** @var string */
        $body = json_encode($data);
        $response = ResponseFactory::createMockResponse($body);

        $wrappedResponse = new WrappedResponse($request, $response);
        static::assertInstanceOf(WrappedResponseInterface::class, $wrappedResponse);
        static::assertSame($response->getContent(), $wrappedResponse->getRawResponse()->getContent());
        static::assertSame($data, $wrappedResponse->getParsedResponse());
    }
}
