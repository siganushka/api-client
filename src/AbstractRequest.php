<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

use Siganushka\ApiClient\Exception\ParseResponseException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class AbstractRequest implements RequestInterface
{
    use ConfigurableOptionsAwareTrait;

    public function send(array $options = [])
    {
        $resolved = $this->resolveOptions($options);
        $response = $this->sendRequest($resolved);

        return $this->parseResponse($response);
    }

    /**
     * Sending request.
     *
     * @param array<int|string, mixed> $options
     *
     * @throws ClientExceptionInterface
     * @throws ServerExceptionInterface
     */
    abstract protected function sendRequest(array $options): ResponseInterface;

    /**
     * Returns parsed response data.
     *
     * @throws ParseResponseException
     *
     * @return mixed
     */
    abstract protected function parseResponse(ResponseInterface $response);
}
