<?php

namespace Vengine\Render\Interfaces;

interface BundleInterface
{
    public function getHtml(): string;

    public function addAsset(AssetInterface $asset): static;

    public function removeAsset(string $name): static;
}
