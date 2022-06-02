<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

use Symfony\Component\OptionsResolver\OptionsResolver;

trait ConfigurableOptionsAwareTrait
{
    private OptionsResolver $resolver;

    /**
     * @param array<int|string, mixed> $options
     *
     * @return array<int|string, mixed>
     */
    public function resolveOptions(array $options = []): array
    {
        $resolver = $this->getResolver();
        $this->configureOptions($resolver);

        return $resolver->resolve($options);
    }

    public function getResolver(): OptionsResolver
    {
        if (!isset($this->resolver)) {
            $this->resolver = new OptionsResolver();
        }

        return $this->resolver;
    }
}
