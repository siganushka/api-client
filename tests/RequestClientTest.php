<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiClient\RequestClient;
use Siganushka\ApiClient\Response\ResponseFactory;
use Siganushka\ApiClient\Tests\Mock\FooRequest;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RequestClientTest extends TestCase
{
    public function testAll(): void
    {
        $data = ['message' => 'hello'];

        /** @var string */
        $body = json_encode($data);
        $response = ResponseFactory::createMockResponse($body);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects(static::any())
            ->method('request')
            ->willReturn($response)
        ;

        $cachePool = new FilesystemAdapter();
        $registry = RequestRegistryTest::createRequestRegistry();
        $client = new RequestClient($httpClient, $cachePool, $registry);

        $options = ['foo' => 'test'];
        $wrappedResponse = $client->send(FooRequest::class, $options);
        static::assertSame($response->getContent(), $wrappedResponse->getRawResponse()->getContent());
        static::assertSame($data, $wrappedResponse->getParsedResponse());
        static::assertFalse($wrappedResponse->isCached());

        $wrappedResponse = $client->send(FooRequest::class, $options);
        static::assertSame($response->getContent(), $wrappedResponse->getRawResponse()->getContent());
        static::assertSame($data, $wrappedResponse->getParsedResponse());
        static::assertTrue($wrappedResponse->isCached());

        $options = ['foo' => 'test2'];
        $wrappedResponse = $client->send(FooRequest::class, $options);
        static::assertSame($response->getContent(), $wrappedResponse->getRawResponse()->getContent());
        static::assertSame($data, $wrappedResponse->getParsedResponse());
        static::assertFalse($wrappedResponse->isCached());

        // clear cache after run tests
        $cachePool->clear();
    }
}
