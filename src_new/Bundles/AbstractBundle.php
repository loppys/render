<?php

namespace Vengine\Render\Bundles;

use Vengine\Render\Assets\Asset;
use Vengine\Render\Collections\AssetCollection;
use Vengine\Render\Interfaces\AssetInterface;
use Vengine\Render\Interfaces\BundleInterface;
use Vengine\Render\Interfaces\TagInterface;
use Vengine\Render\Tags\Tag;

abstract class AbstractBundle implements BundleInterface
{
    protected AssetCollection $collection;

    public function __construct()
    {
        $this->collection = new AssetCollection();
    }

    public function getHtml(): string
    {
        $result = '';

        /** @var AssetInterface $asset */
        foreach ($this->getAssetCollection() as $asset) {
            /** @var TagInterface $tag */
            foreach ($asset->getTagCollection() as $tag) {
                $result .= $tag->getHtml();
            }
        }

        return $result;
    }

    public function getAssetCollection(): AssetCollection
    {
        return $this->collection;
    }

    public function addTag(Tag $tag): static
    {
        $asset = (new Asset())->addTag($tag);

        $this->addAsset($asset);

        return $this;
    }

    public function addAsset(AssetInterface $asset): static
    {
        $this->collection->offsetSet($asset);

        return $this;
    }

    public function removeAsset(string $name): static
    {
        $this->collection->offsetUnset($name);

        return $this;
    }
}
