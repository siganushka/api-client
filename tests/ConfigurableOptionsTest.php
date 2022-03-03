<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiClient\ConfigurableOptionsInterface;
use Siganushka\ApiClient\Tests\Mock\FooOptions;

class ConfigurableOptionsTest extends TestCase
{
    public function testAll(): void
    {
        $foo = new FooOptions();

        static::assertInstanceOf(ConfigurableOptionsInterface::class, $foo);
        static::assertTrue(method_exists($foo, 'resolveOptions'));
        static::assertTrue(method_exists($foo, 'configureOptions'));
    }
}
