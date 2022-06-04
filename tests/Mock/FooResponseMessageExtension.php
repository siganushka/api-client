<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests\Mock;

use Siganushka\ApiClient\AbstractRequestExtension;
use Siganushka\ApiClient\RequestRegistryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FooResponseMessageExtension extends AbstractRequestExtension
{
    private HttpClientInterface $httpClient;
    private RequestRegistryInterface $registry;

    public function __construct(HttpClientInterface $httpClient, RequestRegistryInterface $registry)
    {
        $this->httpClient = $httpClient;
        $this->registry = $registry;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $request = $this->registry->get(FooRequest::class);
        $request->setHttpClient($this->httpClient);

        /** @var array{ message: string } */
        $parsedResponse = $request->send(['a' => 'hello']);

        $resolver->setDefault('foo_response_message', $parsedResponse['message']);
    }

    public static function getExtendedRequests(): iterable
    {
        return [
            BarRequest::class,
        ];
    }
}
