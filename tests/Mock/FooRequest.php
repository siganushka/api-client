<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests\Mock;

use Siganushka\ApiClient\AbstractRequest;
use Siganushka\ApiClient\CacheableResponseInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @method array{ query: array{ foo: string, bar: int } } getOptions()
 */
class FooRequest extends AbstractRequest implements CacheableResponseInterface
{
    /**
     * @var array{ message: string }
     */
    public static $responseData = ['message' => 'hello.'];

    private int $cacheTtl = 60;

    /**
     * @param array<string, mixed> $options
     */
    protected function configureRequest(array $options): void
    {
        $query = [
            'foo' => $options['foo'],
            'bar' => $options['bar'],
        ];

        $this
            ->setMethod('GET')
            ->setUrl('/foo')
            ->setQuery($query)
        ;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('foo');
        $resolver->setDefault('bar', 123);
        $resolver->setAllowedTypes('bar', 'int');
    }

    public function parseResponse(ResponseInterface $response)
    {
        $this->cacheTtl = 3600;

        return $response->toArray();
    }

    public function getCacheTtl(): int
    {
        return $this->cacheTtl;
    }
}
