<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

use Siganushka\Contracts\Registry\ServiceRegistryInterface;

/**
 * Registry pattern for api requests.
 *
 * @method RequestInterface get(string $serviceId)
 */
interface RequestRegistryInterface extends ServiceRegistryInterface
{
}
