<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
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
     *
     * @return mixed
     */
    public function send(string $name, array $options = [])
    {
        $request = $this->registry->get($name);
        $request->build($options);

        $cacheItem = $request instanceof CacheableResponseInterface
            ? $this->cachePool->getItem($request->getUniqueKey())
            : null;

        if ($cacheItem && $cacheItem->isHit()) {
            return $cacheItem->get();
        }

        $method = (string) $request->getMethod();
        $url = (string) $request->getUrl();

        $response = $this->httpClient->request($method, $url, $request->getOptions());
        $parsedResponse = $request->parseResponse($response);

        if ($cacheItem instanceof CacheItemInterface && $request instanceof CacheableResponseInterface) {
            $cacheItem->set($parsedResponse);
            $cacheItem->expiresAfter($request->getCacheTtl());
            $this->cachePool->save($cacheItem);
        }

        return $parsedResponse;
    }
}
