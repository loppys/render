<?php

namespace Vengine\Render\Generators\Entities;

use Vengine\Render\Collections\AssetCollection;
use Vengine\Render\Interfaces\AssetInterface;

class Result
{
    protected AssetCollection $assetCollection;

    public function __construct()
    {
        $this->assetCollection = new AssetCollection();
    }

    public function addAsset(AssetInterface $asset): static
    {
        $this->assetCollection->offsetSet($asset, $asset->getName());

        return $this;
    }

    public function getAssetCollection(): AssetCollection
    {
        return $this->assetCollection;
    }

    public function setAssetCollection(AssetCollection $assetCollection): static
    {
        $this->assetCollection = $assetCollection;

        return $this;
    }
}
