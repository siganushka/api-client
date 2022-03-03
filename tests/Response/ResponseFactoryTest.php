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

        /** @var string */
        $body = json_encode(['message' => 'hello']);
        $response = ResponseFactory::createMockResponse($body, ['response_headers' => $headers]);

        static::assertSame('{"message":"hello"}', $response->getContent());
        static::assertSame(['Accept: application/json'], $response->getInfo('response_headers'));
    }
}
