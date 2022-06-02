<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests\Mock;

use Siganushka\ApiClient\AbstractRequest;
use Siganushka\ApiClient\Response\ResponseFactory;
use Symfony\Component\HttpClient\HttpOptions;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\HttpClient\ResponseInterface;

class BarRequest extends AbstractRequest
{
    /**
     * @var array{ success: int }
     */
    public static $responseData = ['success' => 1];

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('foo_response_message');
    }

    protected function sendRequest(array $options): ResponseInterface
    {
        $data = [
            'message' => $options['foo_response_message'],
        ];

        $httpOptions = new HttpOptions();
        $httpOptions->setBody($data);

        return ResponseFactory::createMockResponseWithJson(static::$responseData);
    }

    protected function parseResponse(ResponseInterface $response)
    {
        return $response->toArray();
    }
}
