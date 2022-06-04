<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface RequestInterface extends ConfigurableOptionsInterface, HttpClientAwareInterface
{
    /**
     * Returns request resolver.
     */
    public function getResolver(): OptionsResolver;

    /**
     * Resolve options and sending request.
     *
     * @param array<string, mixed> $options
     *
     * @return mixed
     */
    public function send(array $options = []);
}
