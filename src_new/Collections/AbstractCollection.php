<?php

namespace Vengine\Render\Collections;

use Iterator;
use Countable;
use ArrayAccess;
use Exception;
use RuntimeException;

abstract class AbstractCollection implements Iterator, Countable, ArrayAccess
{
    protected array $_entities = [];
    protected string $_entityClass;

    /**
     * @throws Exception
     */
    public function __construct(array $entities = [])
    {
        foreach ($entities as $key => $entity) {
            $this->offsetSet($entity, $key);
        }

        $this->rewind();
    }

    public function current(): mixed
    {
        return current($this->_entities);
    }

    public function next(): void
    {
        next($this->_entities);
    }

    public function valid(): bool
    {
        return ($this->current() !== false);
    }

    public function rewind(): void
    {
        reset($this->_entities);
    }

    /**
     * @see use get()
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->_entities[$offset] ?? null;
    }

    /**
     * @see use add()|set()
     */
    public function offsetSet(mixed $offset, mixed $value = null): void
    {
        if ($offset instanceof $this->_entityClass) {
            if (!isset($value)) {
                $this->_entities[] = $offset;
            } else {
                $this->_entities[$value] = $offset;
            }

            return;
        }

        throw new RuntimeException("The specified entity is not allowed for this collection.");
    }

    public function offsetUnset(mixed $offset): void
    {
        if (isset($this->_entities[$offset])) {
            unset($this->_entities[$offset]);
        }
    }

    public function key(): string|int|null
    {
        return key($this->_entities);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->_entities[$offset]);
    }

    public function count(): int
    {
        return count($this->_entities);
    }

    public function insertBefore($findingKey, $entityKey, $entity): bool
    {
        if ($this->offsetExists($findingKey)) {
            $entities = (array)$this->_entities;

            $this->clear();

            foreach ($entities as $index => $selfEntity) {
                if ($index === $findingKey) {
                    $this->offsetSet($entity, $entityKey);
                }

                $this->offsetSet($selfEntity, $index);
            }

            $this->rewind();

            return true;
        }

        return false;
    }

    public function insertAfter($findingKey, $entityKey, $entity): bool
    {
        if ($this->offsetExists($findingKey)) {
            $entities = (array)$this->_entities;

            $this->clear();

            foreach ($entities as $index => $selfEntity) {
                $this->offsetSet($selfEntity, $index);

                if ($index === $findingKey) {
                    $this->offsetSet($entity, $entityKey);
                }
            }

            $this->rewind();

            return true;
        }

        return false;
    }

    public function clear(): void
    {
        $this->_entities = [];
    }

    public function set(string $name, mixed $value): void
    {
        $this->offsetSet($value, $name);
    }

    public function add(string $value): void
    {
        $this->offsetSet($value);
    }

    public function get(string $name): mixed
    {
        return $this->offsetGet($name);
    }
}

