<?php

namespace Render\Engine;

use Render\Engine\DataStorageInterface;

abstract class DefaultStorage implements DataStorageInterface
{
    protected array $data = [];

    public function add(string $name, mixed $value): DataStorageInterface
    {
        if (!array_key_exists($name, $this->data)) {
            $this->data[$name] = $value;
        }

        return $this;
    }

    public function replace(string $name, mixed $value): DataStorageInterface
    {
        if (array_key_exists($name, $this->data)) {
            $this->set($name, $value);
        }

        return $this;
    }

    public function delete(string $name): DataStorageInterface
    {
        if (array_key_exists($name, $this->data)) {
            unset($this->data[$name]);
        }

        return $this;
    }

    public function set(string $name, mixed $value): DataStorageInterface
    {
        $this->data[$name] = $value;

        return $this;
    }

    public function setData(array $data): DataStorageInterface
    {
        $this->data = $data;

        return $this;
    }

    public function getDataByName(string $name): mixed
    {
        if (!array_key_exists($name, $this->data)) {
            return '';
        }

        return $this->data[$name];
    }

    public function getDataList(): array
    {
        return $this->data;
    }
}
