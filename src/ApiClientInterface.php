<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

interface ApiClientInterface
{
    /**
     * @param array<string, mixed> $options
     *
     * @return mixed
     */
    public function send(string $name, array $options = []);
}
