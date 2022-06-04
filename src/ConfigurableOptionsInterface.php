<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface ConfigurableOptionsInterface
{
    /**
     * @param array<int|string, mixed> $options
     *
     * @return array<int|string, mixed>
     */
    public function resolve(array $options = []): array;

    public function configureOptions(OptionsResolver $resolver): void;
}
