<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

use Siganushka\Contracts\Registry\ServiceRegistry;

class RequestRegistry extends ServiceRegistry implements RequestRegistryInterface
{
    /**
     * @param RequestInterface[] $requests
     */
    public function __construct(iterable $requests = [])
    {
        parent::__construct(RequestInterface::class, $requests);
    }
}
