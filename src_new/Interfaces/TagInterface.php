<?php

namespace Vengine\Render\Interfaces;

use Vengine\Render\Collections\TagCollection;

interface TagInterface
{
    public function getHtml(array $dynamicData = [], $rebuild = false): string;

    public function getChildCollection(): ?TagCollection;

    public function setChildCollection(TagCollection $tagCollection): static;

    public function getTagName(): string;

    public function setTagName(string $name): static;

    public function getUniqueName(): string;

    public function setUniqueName(string $uniqueName): static;

    public function getAttributes(): array;

    public function setAttributes(array $attributes): static;

    public function setUseCloseTag(bool $use): static;

    public function isUseCloseTag(): bool;

    public function setInnerText(string $innerText): static;

    public function getInnerText(): string;
}
