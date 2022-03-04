<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

use Symfony\Contracts\HttpClient\ResponseInterface;

interface WrappedResponseInterface
{
    /**
     * Returns raw response object.
     */
    public function getRawResponse(): ResponseInterface;

    /**
     * Returns parsed response.
     *
     * @return mixed
     */
    public function getParsedResponse();

    /**
     * Returns true if response is cached.
     */
    public function isCached(): bool;
}
