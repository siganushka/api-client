<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiClient\Tests\Mock\FooRequest;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

class RequestTest extends TestCase
{
    public function testAll(): void
    {
        $request = new FooRequest();
        static::assertNull($request->getMethod());
        static::assertNull($request->getUrl());
        static::assertSame([], $request->getOptions());

        $request->build(['foo' => 'test']);
        static::assertSame('GET', $request->getMethod());
        static::assertSame('/foo', $request->getUrl());

        $options = $request->getOptions();
        static::assertSame('test', $options['query']['foo']);
        static::assertSame(123, $options['query']['bar']);
    }

    public function testWithOptions(): void
    {
        $request = new FooRequest();
        $request->build(['foo' => 'test', 'bar' => 789]);

        $options = $request->getOptions();
        static::assertSame('test', $options['query']['foo']);
        static::assertSame(789, $options['query']['bar']);
    }

    public function testMissingOptionsException(): void
    {
        $this->expectException(MissingOptionsException::class);
        $this->expectExceptionMessage('The required option "foo" is missing');

        $request = new FooRequest();
        $request->build();
    }

    public function testInvalidOptionsException(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $this->expectExceptionMessage('The option "bar" with value "aaa" is expected to be of type "int", but is of type "string"');

        $request = new FooRequest();
        $request->build(['foo' => 'test', 'bar' => 'aaa']);
    }

    public function testUndefinedOptionsException(): void
    {
        $this->expectException(UndefinedOptionsException::class);
        $this->expectExceptionMessage('The option "baz" does not exist. Defined options are: "bar", "foo"');

        $request = new FooRequest();
        $request->build(['foo' => 'test', 'baz' => '123']);
    }
}
