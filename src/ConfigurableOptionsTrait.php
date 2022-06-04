<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

use Symfony\Component\OptionsResolver\OptionsResolver;

trait ConfigurableOptionsTrait
{
    /**
     * @param array<int|string, mixed> $options
     *
     * @return array<int|string, mixed>
     */
    public function resolve(array $options = []): array
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        return $resolver->resolve($options);
    }
}
