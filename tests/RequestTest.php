<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiClient\Exception\ParseResponseException;
use Siganushka\ApiClient\Response\ResponseFactory;
use Siganushka\ApiClient\Tests\Mock\FooRequest;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Contracts\HttpClient\ResponseInterface;

class RequestTest extends TestCase
{
    public function testAll(): void
    {
        $foo = new FooRequest();

        $resolved = $foo->resolveOptions(['a' => 'hello']);
        static::assertSame('hello', $resolved['a']);
        static::assertSame('world', $resolved['b']);

        $sendRequestRef = new \ReflectionMethod($foo, 'sendRequest');
        $sendRequestRef->setAccessible(true);
        static::assertInstanceOf(ResponseInterface::class, $response = $sendRequestRef->invoke($foo, $resolved));

        $parseResponseRef = new \ReflectionMethod($foo, 'parseResponse');
        $parseResponseRef->setAccessible(true);
        static::assertSame(FooRequest::$responseData, $parseResponseRef->invoke($foo, $response));
    }

    public function testParseResponseException(): void
    {
        $this->expectException(ParseResponseException::class);
        $this->expectExceptionCode(65535);
        $this->expectExceptionMessage('invalid argument error');

        $response = ResponseFactory::createMockResponseWithJson(FooRequest::$responseDataWithError);

        $foo = new FooRequest();
        $parseResponseRef = new \ReflectionMethod($foo, 'parseResponse');
        $parseResponseRef->setAccessible(true);
        $parseResponseRef->invoke($foo, $response);
    }

    public function testMissingOptionsException(): void
    {
        $this->expectException(MissingOptionsException::class);
        $this->expectExceptionMessage('The required option "a" is missing');

        $foo = new FooRequest();
        $foo->resolveOptions();
    }

    public function testInvalidOptionsException(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $this->expectExceptionMessage('The option "c" with value "aaa" is expected to be of type "int", but is of type "string"');

        $foo = new FooRequest();
        $foo->resolveOptions(['a' => 'hello', 'c' => 'aaa']);
    }

    public function testUndefinedOptionsException(): void
    {
        $this->expectException(UndefinedOptionsException::class);
        $this->expectExceptionMessage('The option "d" does not exist. Defined options are: "a", "b", "c"');

        $foo = new FooRequest();
        $foo->resolveOptions(['d' => 'xyz']);
    }
}
