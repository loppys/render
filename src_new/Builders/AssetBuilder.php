<?php

namespace Vengine\Render\Builders;

use Vengine\Render\Bundles\AssetBundle;
use Vengine\Render\Interfaces\AssetInterface;
use Vengine\Render\Interfaces\BundleInterface;

class AssetBuilder extends AbstractBuilder
{
    public function build(): BundleInterface
    {
        $bundle = new AssetBundle();

        /** @var AssetInterface $asset */
        foreach ($this->assetCollection as $asset) {
            $bundle->addAsset($asset);
        }

        return $bundle;
    }
}