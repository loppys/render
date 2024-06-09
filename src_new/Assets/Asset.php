<?php

namespace Vengine\Render\Assets;

use Vengine\Render\Collections\TagCollection;
use Vengine\Render\Interfaces\AssetInterface;
use Vengine\Render\Interfaces\TagInterface;

class Asset implements AssetInterface
{
    protected string $name = '';

    protected TagCollection $tagCollection;

    public function __construct(string $name = '')
    {
        if (empty($name)) {
            $name = uniqid('asset_', true);
        }

        $this->name = $name;
        $this->tagCollection = new TagCollection();
    }

    public function addTag(TagInterface $tag): static
    {
        $this->tagCollection->offsetSet($tag, $tag->getUniqueName());

        return $this;
    }

    public function getTagCollection(): TagCollection
    {
        return $this->tagCollection;
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
}
