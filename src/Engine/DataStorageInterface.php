<?php

namespace Render\Engine;

interface DataStorageInterface
{
    public function add(string $name, mixed $value): DataStorageInterface;

    public function delete(string $name): DataStorageInterface;

    public function set(string $name, mixed $value): DataStorageInterface;

    public function replace(string $name, mixed $value): DataStorageInterface;
}
