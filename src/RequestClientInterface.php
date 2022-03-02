<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

interface RequestClientInterface
{
    /**
     * @param array<string, mixed> $options
     */
    public function send(string $name, array $options = []): WrappedResponseInterface;
}
