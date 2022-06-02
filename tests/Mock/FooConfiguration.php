<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests\Mock;

use Siganushka\ApiClient\AbstractConfiguration;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FooConfiguration extends AbstractConfiguration
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['foo', 'bar']);
        $resolver->setDefault('baz', 65535);

        $resolver->setAllowedTypes('foo', 'string');
        $resolver->setAllowedTypes('bar', 'string');
        $resolver->setAllowedTypes('baz', 'int');
    }
}
