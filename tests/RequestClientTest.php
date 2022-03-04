<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemPoolInterface;
use Siganushka\ApiClient\Exception\ParseResponseException;
use Siganushka\ApiClient\RequestClient;
use Siganushka\ApiClient\RequestClientInterface;
use Siganushka\ApiClient\Response\ResponseFactory;
use Siganushka\ApiClient\Tests\Mock\BarRequestWithParseError;
use Siganushka\ApiClient\Tests\Mock\FooRequest;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class RequestClientTest extends TestCase
{
    public function testAll(): void
    {
        $response = static::createMockResponse(FooRequest::$responseData);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects(static::any())
            ->method('request')
            ->willReturn($response)
        ;

        $cachePool = new FilesystemAdapter();
        $client = static::createRequestClient($httpClient, $cachePool);

        $options = ['foo' => 'test'];
        $wrappedResponse = $client->send(FooRequest::class, $options);
        static::assertSame($response->getContent(), $wrappedResponse->getRawResponse()->getContent());
        static::assertSame(FooRequest::$responseData, $wrappedResponse->getParsedResponse());
        static::assertFalse($wrappedResponse->isCached());

        $wrappedResponse = $client->send(FooRequest::class, $options);
        static::assertSame($response->getContent(), $wrappedResponse->getRawResponse()->getContent());
        static::assertSame(FooRequest::$responseData, $wrappedResponse->getParsedResponse());
        static::assertTrue($wrappedResponse->isCached());

        $options = ['foo' => 'test2'];
        $wrappedResponse = $client->send(FooRequest::class, $options);
        static::assertSame($response->getContent(), $wrappedResponse->getRawResponse()->getContent());
        static::assertSame(FooRequest::$responseData, $wrappedResponse->getParsedResponse());
        static::assertFalse($wrappedResponse->isCached());

        // clear cache after run tests
        $cachePool->clear();
    }

    public function testParseResponseException(): void
    {
        $this->expectException(ParseResponseException::class);
        $this->expectExceptionMessage('invalid argument error');

        $response = static::createMockResponse(BarRequestWithParseError::$responseData);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects(static::any())
            ->method('request')
            ->willReturn($response)
        ;

        $cachePool = new FilesystemAdapter();
        $client = static::createRequestClient($httpClient, $cachePool);

        $wrappedResponse = $client->send(BarRequestWithParseError::class);
        static::assertSame($response->getContent(), $wrappedResponse->getRawResponse()->getContent());
        static::assertFalse($wrappedResponse->isCached());

        $wrappedResponse = $client->send(BarRequestWithParseError::class);
        static::assertSame($response->getContent(), $wrappedResponse->getRawResponse()->getContent());
        static::assertFalse($wrappedResponse->isCached());
    }

    public static function createRequestClient(HttpClientInterface $httpClient, CacheItemPoolInterface $cachePool): RequestClientInterface
    {
        $cachePool = new FilesystemAdapter();
        $registry = RequestRegistryTest::createRequestRegistry();

        return new RequestClient($httpClient, $cachePool, $registry);
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function createMockResponse(array $data): ResponseInterface
    {
        /** @var string */
        $body = json_encode($data);

        return ResponseFactory::createMockResponse($body);
    }
}
