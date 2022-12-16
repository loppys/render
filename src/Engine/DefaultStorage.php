<?php

namespace Render\Engine;

use Render\Engine\DataStorageInterface;

abstract class DefaultStorage implements DataStorageInterface
{
    protected $data = [];

    public function add(string $name, $value): DataStorageInterface
    {
        if (!array_key_exists($name, $this->data)) {
            $this->data[$name] = $value;
        }

        return $this;
    }

    public function replace(string $name, $value): DataStorageInterface
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

    public function set(string $name, $value): DataStorageInterface
    {
        $this->data[$name] = $value;

        return $this;
    }

    public function setData(array $data): DataStorageInterface
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getDataByName(string $name)
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