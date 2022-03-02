<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests\Mock;

use Siganushka\ApiClient\AbstractConfiguration;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FooConfiguration extends AbstractConfiguration
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('foo');
        $resolver->setDefault('bar', 123);
        $resolver->setAllowedTypes('bar', 'int');
    }
}
