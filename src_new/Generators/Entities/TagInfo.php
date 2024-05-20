<?php

namespace Vengine\Render\Generators\Entities;

class TagInfo
{
    protected int $clones = 0;

    protected string $name = '';

    protected string $tagName = '';

    protected bool $useCloseTag = true;

    protected string $innerText = '';

    protected array $attributes = [];

    protected array $cloneNameList = [];

    public function __clone(): void
    {
        $this->clones = 0;
        $this->cloneNameList = [];
    }

    public function getCloneNameList(): array
    {
        return $this->cloneNameList;
    }

    public function addCloneName(string $name): static
    {
        $this->cloneNameList[] = $name;

        return $this;
    }

    public function getCloneCount(): int
    {
        return $this->clones;
    }

    public function setClones(int $cloneCount): static
    {
        $this->clones = $cloneCount;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getTagName(): string
    {
        return $this->tagName;
    }

    public function setTagName(string $tagName): static
    {
        $this->tagName = $tagName;

        return $this;
    }

    public function isUseCloseTag(): bool
    {
        return $this->useCloseTag;
    }

    public function setUseCloseTag(bool $useCloseTag): static
    {
        $this->useCloseTag = $useCloseTag;

        return $this;
    }

    public function getInnerText(): string
    {
        return $this->innerText;
    }

    public function setInnerText(string $innerText): static
    {
        $this->innerText = $innerText;

        return $this;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes): static
    {
        $this->attributes = $attributes;

        return $this;
    }
}
