<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

use Symfony\Component\HttpClient\HttpOptions;

class RequestOptions extends HttpOptions
{
    private string $method;
    private string $url;

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }
}
