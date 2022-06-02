<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiClient\Tests\Mock\FooConfiguration;

class ConfigurationTest extends TestCase
{
    public function testAll(): void
    {
        $configs = [
            'foo' => 'hello',
            'bar' => 'world',
        ];

        $configuration = new FooConfiguration($configs);
        static::assertSame('hello', $configuration['foo']);
        static::assertSame('world', $configuration['bar']);
        static::assertSame(65535, $configuration['baz']);

        $configuration = new FooConfiguration($configs + ['baz' => 1024]);
        static::assertSame('hello', $configuration['foo']);
        static::assertSame('world', $configuration['bar']);
        static::assertSame(1024, $configuration['baz']);
    }
}
