<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiClient\Exception\ParseResponseException;
use Siganushka\ApiClient\RequestClient;
use Siganushka\ApiClient\Response\ResponseFactory;
use Siganushka\ApiClient\Tests\Mock\BarRequestWithParseError;
use Siganushka\ApiClient\Tests\Mock\FooRequest;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RequestClientTest extends TestCase
{
    public function testAll(): void
    {
        $httpClient = $this->createHttpClientWithResponse(FooRequest::$responseData);
        $cachePool = new FilesystemAdapter();
        $registry = RequestRegistryTest::createRequestRegistry();

        $client = new RequestClient($httpClient, $cachePool, $registry);

        $options = ['foo' => 'test'];
        $request = $registry->get(FooRequest::class);
        $request->build($options);

        $cacheItem = $cachePool->getItem($request->getUniqueKey());
        static::assertFalse($cacheItem->isHit());

        $result = $client->send(FooRequest::class, $options);
        static::assertSame(FooRequest::$responseData, $result);

        $cacheItem = $cachePool->getItem($request->getUniqueKey());
        static::assertTrue($cacheItem->isHit());
        static::assertSame(FooRequest::$responseData, $cacheItem->get());

        $result = $client->send(FooRequest::class, $options);
        static::assertSame(FooRequest::$responseData, $result);

        // delete cache after run tests
        $cachePool->deleteItem($request->getUniqueKey());
    }

    public function testParseResponseException(): void
    {
        $this->expectException(ParseResponseException::class);
        $this->expectExceptionMessage('invalid argument error');

        $httpClient = $this->createHttpClientWithResponse(BarRequestWithParseError::$responseData);
        $cachePool = new FilesystemAdapter();
        $registry = RequestRegistryTest::createRequestRegistry();

        $client = new RequestClient($httpClient, $cachePool, $registry);
        $client->send(BarRequestWithParseError::class);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function createHttpClientWithResponse(array $data): HttpClientInterface
    {
        /** @var string */
        $body = json_encode($data);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects(static::any())
            ->method('request')
            ->willReturn(ResponseFactory::createMockResponse($body))
        ;

        return $httpClient;
    }
}
