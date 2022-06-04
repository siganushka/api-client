<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests;

use Siganushka\ApiClient\Exception\ParseResponseException;
use Siganushka\ApiClient\RequestOptions;
use Siganushka\ApiClient\Response\ResponseFactory;
use Siganushka\ApiClient\Tests\Mock\FooRequest;
use Siganushka\ApiClient\Tests\Mock\FooRequestOverrideResponse;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

class RequestTest extends BaseTest
{
    public function testResolve(): void
    {
        $foo = new FooRequest();

        $resolved = $foo->resolve(['a' => 'hello']);
        static::assertSame('hello', $resolved['a']);
        static::assertSame('world', $resolved['b']);
        static::assertSame(['a', 'b', 'c'], $foo->getResolver()->getDefinedOptions());
    }

    public function testSend(): void
    {
        $httpClient = $this->createHttpClient();

        $foo = new FooRequest();
        $foo->setHttpClient($httpClient);

        $parsedResponse = $foo->send(['a' => 'hello']);
        static::assertSame(FooRequest::$responseData, $parsedResponse);
    }

    public function testOverrideResponse(): void
    {
        $httpClient = $this->createHttpClient();

        $foo = new FooRequestOverrideResponse();
        $foo->setHttpClient($httpClient);

        $parsedResponse = $foo->send(['a' => 'hello']);
        static::assertSame(FooRequestOverrideResponse::$responseData, $parsedResponse);
    }

    public function testConfigureRequest(): void
    {
        $foo = new FooRequest();
        $request = new RequestOptions();

        $configureRequestRef = new \ReflectionMethod($foo, 'configureRequest');
        $configureRequestRef->setAccessible(true);
        $configureRequestRef->invoke($foo, $request, $foo->resolve(['a' => 'hello']));

        static::assertSame('GET', $request->getMethod());
        static::assertSame('/foo', $request->getUrl());
        static::assertSame([
            'query' => [
                'options_a' => 'hello',
                'options_b' => 'world',
            ],
        ], $request->toArray());
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
        $foo->resolve();
    }

    public function testInvalidOptionsException(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $this->expectExceptionMessage('The option "c" with value "aaa" is expected to be of type "int", but is of type "string"');

        $foo = new FooRequest();
        $foo->resolve(['a' => 'hello', 'c' => 'aaa']);
    }

    public function testUndefinedOptionsException(): void
    {
        $this->expectException(UndefinedOptionsException::class);
        $this->expectExceptionMessage('The option "d" does not exist. Defined options are: "a", "b", "c"');

        $foo = new FooRequest();
        $foo->resolve(['d' => 'xyz']);
    }
}
