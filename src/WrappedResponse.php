<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

use Symfony\Contracts\HttpClient\ResponseInterface;

class WrappedResponse implements WrappedResponseInterface
{
    protected RequestInterface $request;
    protected ResponseInterface $response;

    public function __construct(RequestInterface $request, ResponseInterface $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function getRawResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function getParsedResponse()
    {
        return $this->request->parseResponse($this->response);
    }
}
