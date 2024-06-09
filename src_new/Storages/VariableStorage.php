<?php

namespace Vengine\Render\Storages;

class VariableStorage
{
    protected static array $globals = [];

    protected array $variables = [];

    public function addGlobalVariable(string $name, mixed $value): static
    {
        static::$globals[$name] = $value;

        return $this;
    }

    public function setVariableByName(string $name, mixed $value): static
    {
        $this->variables[$name] = $value;

        return $this;
    }

    public function addVariable(mixed $value): static
    {
        $this->variables[] = $value;

        return $this;
    }

    public function getGlobalVariableByName(string $name): mixed
    {
        return static::$globals[$name] ?? null;
    }

    public function getVariableByName(string $name): mixed
    {
        return $this->variables[$name] ?? null;
    }

    public function getGlobalVariables(): array
    {
        return static::$globals;
    }

    public function getVariables(): array
    {
        return $this->variables;
    }
}
