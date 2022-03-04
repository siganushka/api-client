<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

use Symfony\Contracts\HttpClient\ResponseInterface;

class WrappedResponse implements WrappedResponseInterface
{
    protected ResponseInterface $response;
    /** @var mixed */
    protected $parsedResponse;
    protected bool $cached;

    /**
     * @param mixed $parsedResponse
     */
    public function __construct(ResponseInterface $response, $parsedResponse, bool $cached = false)
    {
        $this->response = $response;
        $this->parsedResponse = $parsedResponse;
        $this->cached = $cached;
    }

    public function getRawResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function getParsedResponse()
    {
        return $this->parsedResponse;
    }

    public function isCached(): bool
    {
        return $this->cached;
    }
}
