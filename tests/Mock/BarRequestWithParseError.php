<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests\Mock;

use Siganushka\ApiClient\AbstractRequest;
use Siganushka\ApiClient\CacheableResponseInterface;
use Siganushka\ApiClient\Exception\ParseResponseException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @method array{ query: array{ bar: string } } getOptions()
 */
class BarRequestWithParseError extends AbstractRequest implements CacheableResponseInterface
{
    /**
     * @var array{ error: string }
     */
    public static $responseData = ['error' => 'invalid argument error.'];

    /**
     * @param array<string, mixed> $options
     */
    protected function configureRequest(array $options): void
    {
        $query = [
            'bar' => $options['bar'],
        ];

        $this
            ->setMethod('POST')
            ->setUrl('/bar')
            ->setQuery($query)
        ;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('bar', 'hello');
    }

    public function parseResponse(ResponseInterface $response)
    {
        $data = $response->toArray();
        throw new ParseResponseException($response, $data['error']);
    }

    public function getCacheTtl(): int
    {
        return 60;
    }
}
