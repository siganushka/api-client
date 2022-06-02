<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

interface RequestExtensionInterface extends ConfigurableOptionsInterface
{
    /**
     * @return iterable<string>
     */
    public static function getExtendedRequests(): iterable;
}
