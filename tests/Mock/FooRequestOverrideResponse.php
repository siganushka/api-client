<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests\Mock;

use Siganushka\ApiClient\RequestOptions;
use Siganushka\ApiClient\Response\ResponseFactory;
use Symfony\Contracts\HttpClient\ResponseInterface;

class FooRequestOverrideResponse extends FooRequest
{
    /**
     * @var array{ message: string }
     */
    public static $responseData = ['message' => 'hello siganushka'];

    protected function sendRequest(RequestOptions $request): ResponseInterface
    {
        return ResponseFactory::createMockResponseWithJson(['message' => 'hello siganushka']);
    }
}
