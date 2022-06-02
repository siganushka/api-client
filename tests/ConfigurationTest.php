<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiClient\Tests\Mock\FooConfiguration;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

class ConfigurationTest extends TestCase
{
    public function testAll(): void
    {
        $options = [
            'foo' => 'Hello PHP.',
        ];

        $configuration = new FooConfiguration($options);
        static::assertSame($options['foo'], $configuration['foo']);
        static::assertSame(123, $configuration['bar']);
    }

    public function testWithOptions(): void
    {
        $options = [
            'foo' => 'Hello Symfony.',
            'bar' => 1024,
        ];

        $configuration = new FooConfiguration($options);
        static::assertSame($options['foo'], $configuration['foo']);
        static::assertSame($options['bar'], $configuration['bar']);
    }

    public function testMissingOptionsException(): void
    {
        $this->expectException(MissingOptionsException::class);
        $this->expectExceptionMessage('The required option "foo" is missing');

        new FooConfiguration();
    }

    public function testInvalidOptionsException(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $this->expectExceptionMessage('The option "bar" with value "aaa" is expected to be of type "int", but is of type "string"');

        new FooConfiguration(['foo' => 'test', 'bar' => 'aaa']);
    }

    public function testUndefinedOptionsException(): void
    {
        $this->expectException(UndefinedOptionsException::class);
        $this->expectExceptionMessage('The option "baz" does not exist. Defined options are: "bar", "foo"');

        new FooConfiguration(['foo' => 'test', 'baz' => '123']);
    }
}
