<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

use Symfony\Contracts\HttpClient\ResponseInterface;

interface WrappedResponseInterface
{
    public function getRawResponse(): ResponseInterface;

    /**
     * @return mixed
     */
    public function getParsedResponse();
}
