<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests\Mock;

use Siganushka\ApiClient\AbstractRequest;
use Siganushka\ApiClient\RequestOptions;
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

    protected function configureRequest(RequestOptions $request, array $options): void
    {
        $data = [
            'message' => $options['foo_response_message'],
        ];

        $request
            ->setMethod('POST')
            ->setUrl('/bar')
            ->setBody($data)
        ;
    }

    /**
     * @return array{ success: int }
     */
    protected function parseResponse(ResponseInterface $response): array
    {
        /**
         * @var array{ success: int }
         */
        $parsedResponse = $response->toArray();

        return $parsedResponse;
    }
}
