<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface ConfigurableOptionsInterface
{
    /**
     * @param array<string, mixed> $options
     *
     * @return array<string, mixed>
     */
    public function resolveOptions(array $options = []): array;

    public function configureOptions(OptionsResolver $resolver): void;
}
