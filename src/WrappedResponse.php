<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

use Symfony\Contracts\HttpClient\ResponseInterface;

class WrappedResponse implements WrappedResponseInterface
{
    protected RequestInterface $request;
    protected ResponseInterface $response;
    protected bool $cached;

    public function __construct(RequestInterface $request, ResponseInterface $response, bool $cached = false)
    {
        $this->request = $request;
        $this->response = $response;
        $this->cached = $cached;
    }

    public function getRawResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function getParsedResponse()
    {
        return $this->request->parseResponse($this->response);
    }

    public function isCached(): bool
    {
        return $this->cached;
    }
}
