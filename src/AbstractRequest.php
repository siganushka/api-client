<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

use Symfony\Component\HttpClient\HttpOptions;

abstract class AbstractRequest extends HttpOptions implements RequestInterface
{
    use ConfigurableOptionsTrait;

    private ?string $method = null;
    private ?string $url = null;

    protected function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    protected function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getOptions(): array
    {
        return $this->toArray();
    }

    public function getUniqueKey(): string
    {
        return sprintf('%s_%s', static::class, md5(serialize($this->getOptions())));
    }

    /**
     * @param array<int|string, mixed> $options
     */
    public function build(array $options = []): void
    {
        $resolved = $this->resolveOptions($options);
        $this->configureRequest($resolved);
    }

    /**
     * @param array<int|string, mixed> $options
     */
    abstract protected function configureRequest(array $options): void;
}
