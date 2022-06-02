<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface ConfigurableOptionsAwareInterface extends ConfigurableOptionsInterface
{
    public function getResolver(): OptionsResolver;
}
