<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiClient\Exception\ParseResponseException;
use Siganushka\ApiClient\Response\ResponseFactory;

class ParseResponseExceptionTest extends TestCase
{
    public function testAll(): void
    {
        $response = ResponseFactory::createMockResponse('test');
        $previous = new \InvalidArgumentException('foo');

        $exception = new ParseResponseException($response, 'bar', 1024, $previous);
        static::assertInstanceOf(\Throwable::class, $exception);
        static::assertSame($response, $exception->getResponse());
        static::assertSame('test', $exception->getResponse()->getContent());
        static::assertSame($previous, $exception->getPrevious());
        static::assertSame('bar', $exception->getMessage());
        static::assertSame(1024, $exception->getCode());
    }
}
