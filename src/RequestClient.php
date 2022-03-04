<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Siganushka\ApiClient\Response\ResponseFactory;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RequestClient implements RequestClientInterface
{
    private HttpClientInterface $httpClient;
    private CacheItemPoolInterface $cachePool;
    private RequestRegistryInterface $registry;

    public function __construct(HttpClientInterface $httpClient, CacheItemPoolInterface $cachePool, RequestRegistryInterface $registry)
    {
        $this->httpClient = $httpClient;
        $this->cachePool = $cachePool;
        $this->registry = $registry;
    }

    /**
     * @param array<string, mixed> $options
     */
    public function send(string $name, array $options = []): WrappedResponseInterface
    {
        $request = $this->registry->get($name);
        $request->build($options);

        $cacheItem = $request instanceof CacheableResponseInterface
            ? $this->createCacheItem($request, $name)
            : null;

        if ($cacheItem && $cacheItem->isHit()) {
            /** @var string */
            $body = $cacheItem->get();
            $response = ResponseFactory::createMockResponse($body);

            return new WrappedResponse($response, $request->parseResponse($response), true);
        }

        $method = (string) $request->getMethod();
        $url = (string) $request->getUrl();

        $response = $this->httpClient->request($method, $url, $request->getOptions());
        $parsedResponse = $request->parseResponse($response);

        if ($cacheItem instanceof CacheItemInterface && $request instanceof CacheableResponseInterface) {
            $cacheItem->set($response->getContent());
            $cacheItem->expiresAfter($request->getCacheTtl());
            $this->cachePool->save($cacheItem);
        }

        return new WrappedResponse($response, $parsedResponse);
    }

    private function createCacheItem(RequestInterface $request, string $keyPrefix): CacheItemInterface
    {
        return $this->cachePool->getItem(sprintf('%s_%s', $keyPrefix, md5(serialize($request->getOptions()))));
    }
}
