<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests\Mock;

use Siganushka\ApiClient\AbstractRequest;
use Siganushka\ApiClient\Exception\ParseResponseException;
use Siganushka\ApiClient\RequestOptions;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\HttpClient\ResponseInterface;

class FooRequest extends AbstractRequest
{
    /**
     * @var array{ message: string }
     */
    public static $responseData = ['message' => 'hello world'];

    /**
     * @var array{ err_code: int, err_msg: string }
     */
    public static $responseDataWithError = ['err_code' => 65535, 'err_msg' => 'invalid argument error.'];

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('a');
        $resolver->setDefault('b', 'world');
        $resolver->setDefined('c');

        $resolver->setAllowedTypes('a', 'string');
        $resolver->setAllowedTypes('b', 'string');
        $resolver->setAllowedTypes('c', 'int');
    }

    protected function configureRequest(RequestOptions $request, array $options): void
    {
        $query = [
            'options_a' => $options['a'],
            'options_b' => $options['b'],
        ];

        if (isset($options['c'])) {
            $query['options_c'] = $options['c'];
        }

        $request
            ->setMethod('GET')
            ->setUrl('/foo')
            ->setQuery($query)
        ;
    }

    /**
     * @return array{ message: string, err_code?: int, err_msg?: string }
     */
    protected function parseResponse(ResponseInterface $response): array
    {
        /**
         * @var array{ message: string, err_code?: int, err_msg?: string }
         */
        $parsedResponse = $response->toArray();

        $errCode = (int) ($parsedResponse['err_code'] ?? 0);
        $errMsg = (string) ($parsedResponse['err_msg'] ?? '');

        if (0 === $errCode) {
            return $parsedResponse;
        }

        throw new ParseResponseException($response, $errMsg, $errCode);
    }
}
