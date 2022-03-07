<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

/**
 * Http client decorated for api requests.
 */
interface RequestClientInterface
{
    /**
     * @param array<string, mixed> $options
     */
    public function send(string $name, array $options = []): WrappedResponseInterface;
}
