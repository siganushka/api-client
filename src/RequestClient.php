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

        if ($cacheItem instanceof CacheItemInterface && $cacheItem->isHit()) {
            /** @var string */
            $body = $cacheItem->get();

            return $this->createWrappedResponse($request, $body);
        }

        $method = (string) $request->getMethod();
        $url = (string) $request->getUrl();

        $response = $this->httpClient->request($method, $url, $request->getOptions());
        $body = $response->getContent();

        if ($cacheItem instanceof CacheItemInterface) {
            $cacheItem->set($body);
            $this->cachePool->save($cacheItem);
        }

        return $this->createWrappedResponse($request, $body);
    }

    private function createCacheItem(RequestInterface $request, string $keyPrefix): CacheItemInterface
    {
        $cacheItem = $this->cachePool->getItem(sprintf('%s_%s', $keyPrefix, md5(serialize($request))));
        if ($request instanceof CacheableResponseInterface) {
            $cacheItem->expiresAfter($request->getCacheTtl());
        }

        return $cacheItem;
    }

    private function createWrappedResponse(RequestInterface $request, string $body): WrappedResponseInterface
    {
        return new WrappedResponse($request, ResponseFactory::createMockResponse($body));
    }
}
