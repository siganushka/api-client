<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiClient\RequestClient;
use Siganushka\ApiClient\Tests\Mock\FooRequest;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RequestClientTest extends TestCase
{
    public function testAll(): void
    {
        /** @var string */
        $body = json_encode(['message' => 'hello']);

        $response = new MockResponse($body);
        $response = MockResponse::fromRequest('GET', '/', [], $response);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects(static::any())
            ->method('request')
            ->willReturn($response)
        ;

        $registry = RequestRegistryTest::createRequestRegistry();
        $client = new RequestClient($httpClient, $registry);

        $wrappedResponse = $client->send(FooRequest::class, ['foo' => 'test']);
        static::assertSame($response, $wrappedResponse->getRawResponse());
        static::assertSame($body, $wrappedResponse->getParsedBody());
    }
}
