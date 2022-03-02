<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class RequestClient implements RequestClientInterface
{
    private HttpClientInterface $client;
    private RequestRegistryInterface $registry;

    public function __construct(HttpClientInterface $client, RequestRegistryInterface $registry)
    {
        $this->client = $client;
        $this->registry = $registry;
    }

    /**
     * @param array<string, mixed> $options
     */
    public function send(string $name, array $options = []): WrappedResponseInterface
    {
        $request = $this->registry->get($name);
        $request->build($options);

        $method = (string) $request->getMethod();
        $url = (string) $request->getUrl();

        $response = $this->client->request($method, $url, $request->getOptions());

        return new WrappedResponse($request, $response);
    }
}
