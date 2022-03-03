<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

interface ConfigurableOptionsInterface
{
    /**
     * @param array<string, mixed> $options
     *
     * @return array<string, mixed>
     */
    public function resolveOptions(array $options): array;
}
