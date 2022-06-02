<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

interface RequestInterface extends ConfigurableOptionsAwareInterface
{
    /**
     * Resolve options and sending request.
     *
     * @param array<string, mixed> $options
     *
     * @return mixed
     */
    public function send(array $options = []);
}
