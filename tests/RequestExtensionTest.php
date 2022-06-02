<?php

declare(strict_types=1);

namespace Siganushka\ApiClient\Tests;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiClient\Tests\Mock\FooResponseMessageExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RequestExtensionTest extends TestCase
{
    public function testAll(): void
    {
        $registry = RequestRegistryTest::createRequestRegistry();

        $resolver = new OptionsResolver();
        static::assertSame([], $resolver->resolve());

        $extension = new FooResponseMessageExtension($registry);
        $extension->configureOptions($resolver);

        static::assertSame(['foo_response_message' => 'hello world'], $resolver->resolve());
    }
}
