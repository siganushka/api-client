<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests\Response;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiClient\Response\ResponseFactory;

class ResponseFactoryTest extends TestCase
{
    public function testAll(): void
    {
        $headers = [
            'Accept' => 'application/json',
        ];

        $data = ['message' => 'hello'];
        $response = ResponseFactory::createMockResponseWithJson($data, ['response_headers' => $headers]);

        static::assertSame($data, $response->toArray());
        static::assertSame(['Accept: application/json'], $response->getInfo('response_headers'));
    }
}
