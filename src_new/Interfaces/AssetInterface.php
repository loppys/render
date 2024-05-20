<?php

namespace Vengine\Render\Interfaces;

use Vengine\Render\Collections\TagCollection;

interface AssetInterface
{
    public function addTag(TagInterface $tag): static;

    public function getTagCollection(): TagCollection;

    public function getName(): string;

    public function setName(string $name): static;
}
