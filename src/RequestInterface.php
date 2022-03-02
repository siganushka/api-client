<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

use Siganushka\ApiClient\Exception\ParseResponseException;
use Symfony\Contracts\HttpClient\ResponseInterface;

interface RequestInterface extends ConfigurableOptionsInterface
{
    public function getMethod(): ?string;

    public function getUrl(): ?string;

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array;

    /**
     * @param array<string, mixed> $options
     */
    public function build(array $options = []): void;

    /**
     * @return mixed
     *
     * @throws ParseResponseException
     */
    public function parseResponse(ResponseInterface $response);
}
