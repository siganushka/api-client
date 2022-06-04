<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class AbstractRequest implements RequestInterface
{
    use HttpClientAwareTrait;

    private OptionsResolver $resolver;

    /**
     * @param array<int|string, mixed> $options
     *
     * @return array<int|string, mixed>
     */
    public function resolve(array $options = []): array
    {
        $resolver = $this->getResolver();
        $this->configureOptions($resolver);

        return $resolver->resolve($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
    }

    public function getResolver(): OptionsResolver
    {
        if (!isset($this->resolver)) {
            $this->resolver = new OptionsResolver();
        }

        return $this->resolver;
    }

    public function send(array $options = [])
    {
        $request = new RequestOptions();

        $resolved = $this->resolve($options);
        $this->configureRequest($request, $resolved);

        $response = $this->sendRequest($request);

        return $this->parseResponse($response);
    }

    protected function sendRequest(RequestOptions $request): ResponseInterface
    {
        $method = $request->getMethod();
        $url = $request->getUrl();

        return $this->httpClient->request($method, $url, $request->toArray());
    }

    /**
     * Building request.
     *
     * @param array<int|string, mixed> $options
     */
    abstract protected function configureRequest(RequestOptions $request, array $options): void;

    /**
     * Returns parsed response content.
     *
     * @return mixed
     */
    abstract protected function parseResponse(ResponseInterface $response);
}
