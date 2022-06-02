<?php

declare(strict_types=1);

namespace Siganushka\ApiClient;

use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

/**
 * @implements \ArrayAccess<int|string, mixed>
 */
abstract class AbstractConfiguration implements \ArrayAccess, ConfigurableOptionsInterface
{
    use ConfigurableOptionsTrait;

    /**
     * @var array<int|string, mixed>
     */
    private array $configs;

    /**
     * @param array<int|string, mixed> $configs
     */
    public function __construct(array $configs = [])
    {
        $this->configs = $this->resolveOptions($configs);
    }

    /**
     * @param int|string $offset
     */
    public function offsetExists($offset): bool
    {
        return isset($this->configs[$offset]) || \array_key_exists($offset, $this->configs);
    }

    /**
     * @param int|string $offset
     *
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new UndefinedOptionsException(sprintf('The option "%s" does not exist.', $offset));
        }

        return $this->configs[$offset];
    }

    /**
     * @param int|string|null $offset
     * @param mixed           $value
     */
    public function offsetSet($offset, $value): void
    {
        if (null === $offset) {
            $this->configs[] = $value;
        } else {
            $this->configs[$offset] = $value;
        }
    }

    /**
     * @param int|string $offset
     */
    public function offsetUnset($offset): void
    {
        unset($this->configs[$offset]);
    }

    /**
     * @return array<int|string, mixed>
     */
    public function toArray(): array
    {
        return $this->configs;
    }
}
