<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiClient\Tests\Mock\FooRequest;
use Siganushka\ApiClient\WrappedResponse;
use Siganushka\ApiClient\WrappedResponseInterface;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\ResponseInterface;

class WrappedResponseTest extends TestCase
{
    public function testAll(): void
    {
        $request = new FooRequest();

        /** @var string */
        $body = json_encode(['message' => 'hello']);
        $response = static::createMockResponse($body);

        $wrappedResponse = new WrappedResponse($request, $response);
        static::assertInstanceOf(WrappedResponseInterface::class, $wrappedResponse);
        static::assertSame($response, $wrappedResponse->getRawResponse());
        static::assertSame($body, $wrappedResponse->getParsedBody());
    }

    public static function createMockResponse(string $body): ResponseInterface
    {
        $response = new MockResponse($body);
        $response = MockResponse::fromRequest('GET', '/', [], $response);

        return $response;
    }
}
