<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

use Symfony\Contracts\HttpClient\HttpClientInterface;

trait HttpClientAwareTrait
{
    private HttpClientInterface $httpClient;

    public function setHttpClient(HttpClientInterface $httpClient): void
    {
        $this->httpClient = $httpClient;
    }
}
