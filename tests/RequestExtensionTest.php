<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests;

use Siganushka\ApiClient\Tests\Mock\FooResponseMessageExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RequestExtensionTest extends BaseTest
{
    public function testAll(): void
    {
        $httpClient = $this->createHttpClient();
        $registry = RequestRegistryTest::createRequestRegistry($httpClient);

        $resolver = new OptionsResolver();
        static::assertSame([], $resolver->resolve());

        $extension = new FooResponseMessageExtension($registry);
        $extension->configureOptions($resolver);

        static::assertSame(['foo_response_message' => 'hello world'], $resolver->resolve());
    }
}
