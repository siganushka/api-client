<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests\Mock;

use Siganushka\ApiClient\ConfigurableOptionsInterface;
use Siganushka\ApiClient\ConfigurableOptionsTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FooOptions implements ConfigurableOptionsInterface
{
    use ConfigurableOptionsTrait;

    protected function configureOptions(OptionsResolver $resolver): void
    {
    }
}
