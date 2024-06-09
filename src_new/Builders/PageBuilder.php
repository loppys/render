<?php

namespace Vengine\Render\Builders;

use Vengine\Render\Assets\Asset;
use Vengine\Render\Assets\HeadAsset;
use Vengine\Render\Bundles\AssetBundle;
use Vengine\Render\Bundles\PageBundle;
use Vengine\Render\Bundles\TemplateBundle;
use Vengine\Render\Collections\TagCollection;
use Vengine\Render\Exceptions\RenderException;
use Vengine\Render\Interfaces\AssetInterface;
use Vengine\Render\Interfaces\BundleInterface;
use Vengine\Render\Interfaces\TagInterface;
use Vengine\Render\Tags\Tag;

class PageBuilder extends AbstractBuilder
{
    /**
     * @var array<AssetBundle>
     */
    protected array $assetBundleList = [];

    /**
     * @var array<TemplateBundle>
     */
    protected array $templateBundleList = [];

    protected HeadAsset $headAsset;

    /**
     * @throws RenderException
     */
    public function build(string $title = 'Default Title', string $lang = 'ru'): BundleInterface
    {
        $bundle = new PageBundle();

        $htmlChild = new TagCollection();
        $html = (new Tag('html'))->setAttributes([
            'lang' => '<?php print $lang ?>'
        ]);

        $head = (new Tag());
        $headChild = new TagCollection();

        if (empty($this->headAsset)) {
            $this->headAsset = new HeadAsset($title, $lang);
        }

        foreach ($this->headAsset->getTagCollection() as $headTag) {
            $headChild->offsetSet($headTag);
        }

        $head->setChildCollection($headChild);

        $bodyChild = new TagCollection();
        $body = new Tag('body');

        foreach ($this->templateBundleList as $templateBundle) {
            $bodyChild->offsetSet(
                (new Tag())->setHtml($templateBundle->getHtml())
            );
        }

        foreach ($this->assetBundleList as $assetBundle) {
            /** @var Asset $aa */
            foreach ($assetBundle->getAssetCollection() as $aa) {
                /** @var TagInterface $at */
                foreach ($aa->getTagCollection() as $at) {
                    $bodyChild->offsetSet($at);
                }
            }
        }

        /** @var AssetInterface $asset */
        foreach ($this->assetCollection as $asset) {
            /** @var TagInterface $tt */
            foreach ($asset->getTagCollection() as $tt) {
                $bodyChild->offsetSet($tt);
            }
        }

        /** @var TagInterface $tag */
        foreach ($this->tagCollection as $tag) {
            $bodyChild->offsetSet($tag);
        }

        $body->setChildCollection($bodyChild);

        $htmlChild->offsetSet($head);
        $htmlChild->offsetSet($body);

        $html->setChildCollection($htmlChild);

        $bundle->addTag($html);

        return $bundle;
    }

    public function setHeadAsset(HeadAsset $headAsset): static
    {
        $this->headAsset = $headAsset;

        return $this;
    }

    public function addAssetBundle(AssetBundle $assetBundle): static
    {
        $this->assetBundleList[] = $assetBundle;

        return $this;
    }

    public function addTemplateBundle(TemplateBundle $templateBundle): static
    {
        $this->templateBundleList[] = $templateBundle;

        return $this;
    }
}