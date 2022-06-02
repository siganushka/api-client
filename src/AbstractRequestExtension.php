<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

abstract class AbstractRequestExtension implements RequestExtensionInterface
{
    use ConfigurableOptionsTrait;
}
