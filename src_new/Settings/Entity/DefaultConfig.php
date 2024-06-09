<?php

namespace Vengine\Render\Settings\Entity;

class DefaultConfig
{
    protected string $path = '';

    protected string $ext = '';

    public function __construct(string $path = '')
    {
        $this->setPath($path);
    }

    public function getExt(): string
    {
        return $this->ext;
    }

    public function setExt(string $ext): static
    {
        $this->ext = $ext;

        return $this;
    }

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
