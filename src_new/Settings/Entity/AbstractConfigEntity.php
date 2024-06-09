<?php

namespace Vengine\Render\Settings\Entity;

abstract class AbstractConfigEntity
{
    protected string $path = '';

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }
}
