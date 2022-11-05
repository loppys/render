<?php

namespace Render\Engine;

abstract class DefaultStorage
{
    protected $data = [];

    public function add(string $name, $value): self
    {
        if (!array_key_exists($name, $this->data)) {
            $this->data[$name] = $value;
        }

        return $this;
    }

    public function replace(string $name, $value): self
    {
        if (array_key_exists($name, $this->data)) {
            $this->set($name, $value);
        }

        return $this;
    }

    public function delete(string $name): self
    {
        if (array_key_exists($name, $this->data)) {
            unset($this->data[$name]);
        }

        return $this;
    }

    public function set(string $name, $value): self
    {
        $this->data[$name] = $value;

        return $this;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getDataByName(string $name): mixed
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
    }

    public function getDataList(): array
    {
        return $this->data;
    }
}