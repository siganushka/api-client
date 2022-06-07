<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

use Siganushka\Contracts\Registry\ServiceRegistryInterface;

/**
 * Interface for request registry.
 *
 * @method RequestInterface get(string $serviceId)
 */
interface RequestRegistryInterface extends ServiceRegistryInterface
{
}
