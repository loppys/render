<?php

namespace Vengine\Render\Builders;

use Vengine\Render\Collections\AssetCollection;
use Vengine\Render\Collections\TagCollection;
use Vengine\Render\Interfaces\AssetInterface;
use Vengine\Render\Interfaces\BuilderInterface;
use Vengine\Render\Interfaces\BundleInterface;
use Vengine\Render\Interfaces\TagInterface;
use Vengine\Render\Storages\MessageBuffer;

abstract class AbstractBuilder implements BuilderInterface
{
    protected AssetCollection $assetCollection;

    protected TagCollection $tagCollection;

    protected MessageBuffer $buffer;

    protected string $dataKey = '';

    public function __construct()
    {
        $this->assetCollection = new AssetCollection();
        $this->tagCollection = new TagCollection();
        $this->buffer = MessageBuffer::getInstance();
    }

    abstract public function build(): BundleInterface;

    public function getDataKey(): string
    {
        if (empty($this->dataKey)) {
            $this->dataKey = md5($_SERVER['REQUEST_URI']);
        }

        return $this->dataKey;
    }

    public function setDataKey(string $key): static
    {
        $this->dataKey = $key;

        return $this;
    }

    public function addAsset(AssetInterface $asset, ?string $name = null): static
    {
        $this->assetCollection->offsetSet($asset, $name);

        return $this;
    }

    public function getAsset(string $name): AssetInterface
    {
        /** @var AssetInterface $asset */
        $asset = $this->assetCollection->offsetGet($name);

        return $asset;
    }

    public function removeAsset(string $name): static
    {
        $this->assetCollection->offsetUnset($name);

        return $this;
    }

    public function addTag(TagInterface $tag, ?string $name = null): static
    {
        $this->tagCollection->offsetSet($tag, $name);

        return $this;
    }

    public function getTag(string $name): ?TagInterface
    {
        /** @var TagInterface $tag */
        $tag = $this->tagCollection->offsetGet($name);

        return $tag;
    }

    public function removeTag(string $name): static
    {
        $this->tagCollection->offsetUnset($name);

        return $this;
    }

    public function getMessageBuffer(): MessageBuffer
    {
        return $this->buffer;
    }
}
