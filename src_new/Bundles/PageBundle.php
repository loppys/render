<?php

namespace Vengine\Render\Bundles;

use Vengine\Render\Exceptions\RenderException;
use Vengine\Render\Interfaces\AssetInterface;
use Vengine\Render\Interfaces\TagInterface;

class PageBundle extends AbstractBundle
{
    /**
     * @var array<AssetBundle>
     */
    protected array $assetBundles = [];

    /**
     * @var array<TemplateBundle>
     */
    protected array $templateBundles = [];

    /**
     * @throws RenderException
     */
    public function getHtml(): string
    {
        $result = '';

        foreach ($this->templateBundles as $templateBundle) {
            $result .= $templateBundle->getHtml();
        }

        foreach ($this->assetBundles as $assetBundle) {
            $result .= $assetBundle->getHtml();
        }

        /** @var AssetInterface $asset */
        foreach ($this->collection as $asset) {
            /** @var TagInterface $tag */
            foreach ($asset->getTagCollection() as $tag) {
                $result .= $tag->getHtml();
            }
        }

        return $result;
    }

    public function addAssetBundle(AssetBundle $bundle): static
    {
        $this->assetBundles[] = $bundle;

        return $this;
    }

    public function addTemplateBundle(TemplateBundle $templateBundle): static
    {
        $this->templateBundles[] = $templateBundle;

        return $this;
    }
}
