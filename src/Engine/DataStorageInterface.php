<?php

namespace Render\Engine;

interface DataStorageInterface
{
    public function add(string $name, $value): DataStorageInterface;

    public function delete(string $name): DataStorageInterface;

    public function set(string $name, $value): DataStorageInterface;

    public function replace(string $name, $value): DataStorageInterface;
}