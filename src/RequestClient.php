<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class RequestClient implements RequestClientInterface
{
    private HttpClientInterface $httpClient;
    private RequestRegistryInterface $registry;

    /**
     * @var RequestExtensionInterface[]
     */
    private iterable $extensions;

    /**
     * @param RequestExtensionInterface[] $extensions
     */
    public function __construct(HttpClientInterface $httpClient, RequestRegistryInterface $registry, iterable $extensions = [])
    {
        foreach ($extensions as $extension) {
            if (!$extension instanceof RequestExtensionInterface) {
                throw new \UnexpectedValueException(sprintf('Expected argument of type "%s", "%s" given', RequestExtensionInterface::class, get_debug_type($extension)));
            }
        }

        $this->httpClient = $httpClient;
        $this->registry = $registry;
        $this->extensions = $extensions;
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return mixed
     */
    public function send(string $name, array $options = [])
    {
        $request = $this->registry->get($name);
        foreach ($this->getExtensionsForRequest($request) as $extension) {
            $extension->configureOptions($request->getResolver());
        }

        if ($request instanceof HttpClientAwareInterface) {
            $request->setHttpClient($this->httpClient);
        }

        return $request->send($options);
    }

    /**
     * @return RequestExtensionInterface[]
     */
    public function getExtensionsForRequest(RequestInterface $request): iterable
    {
        $extensions = [];
        foreach ($this->extensions as $extension) {
            foreach ($extension::getExtendedRequests() as $requestClass) {
                if ($request instanceof $requestClass) {
                    $extensions[] = $extension;
                }
            }
        }

        return $extensions;
    }
}
