<?php

namespace Vengine\Render\Generators\Entities;

use Vengine\Render\Collections\TagInfoCollection;

class Map
{
    protected TagInfoCollection $tagInfoCollection;

    public function __construct()
    {
        $this->tagInfoCollection = new TagInfoCollection();
    }

    public function setTagInfoCollection(TagInfoCollection $tagTree): static
    {
        $this->tagInfoCollection = $tagTree;

        return $this;
    }

    public function getTagInfoCollection(): TagInfoCollection
    {
        return $this->tagInfoCollection;
    }

    // default callback method
    public function cache(): static
    {
        return $this;
    }

    // sort methods
    public function priorityManualAddition(): static
    {
        return $this;
    }

    public function priorityGenerateAddition(): static
    {
        return $this;
    }
}
