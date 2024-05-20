<?php

namespace Vengine\Render\Interfaces;

use Vengine\Render\Storages\MessageBuffer;

interface BuilderInterface
{
    public function build(): BundleInterface;

    public function getDataKey(): string;

    public function setDataKey(string $key): static;

    public function addAsset(AssetInterface $asset): static;

    public function getAsset(string $name): AssetInterface;

    public function removeAsset(string $name): static;

    public function addTag(TagInterface $tag): static;

    public function getTag(string $name): ?TagInterface;

    public function removeTag(string $name): static;

    public function getMessageBuffer(): MessageBuffer;
}
