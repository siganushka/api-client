<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

/**
 * Interface for request client.
 */
interface RequestClientInterface
{
    /**
     * @param array<string, mixed> $options
     *
     * @return mixed
     */
    public function send(string $name, array $options = []);
}
