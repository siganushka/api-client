<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

use Symfony\Contracts\HttpClient\HttpClientInterface;

interface HttpClientAwareInterface
{
    public function setHttpClient(HttpClientInterface $httpClient): void;
}
