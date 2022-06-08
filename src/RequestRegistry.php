<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

use Siganushka\Contracts\Registry\ServiceRegistry;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RequestRegistry extends ServiceRegistry implements RequestRegistryInterface
{
    private HttpClientInterface $httpClient;

    /**
     * @param RequestInterface[] $requests
     */
    public function __construct(HttpClientInterface $httpClient, iterable $requests = [])
    {
        $this->httpClient = $httpClient;

        parent::__construct(RequestInterface::class, $requests);
    }

    public function get(string $name): object
    {
        $request = parent::get($name);
        if ($request instanceof HttpClientAwareInterface) {
            $request->setHttpClient($this->httpClient);
        }

        return $request;
    }
}
