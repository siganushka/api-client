<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

interface CacheableResponseInterface
{
    public function getCacheTtl(): int;
}
