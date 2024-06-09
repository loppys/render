<?php

namespace Vengine\Render\Assets;

use Vengine\Render\Collections\TagCollection;
use Vengine\Render\Interfaces\TagInterface;
use Vengine\Render\Tags\Tag;

class HeadAsset extends Asset
{
    protected string $title = '';

    protected string $lang = '';

    public function __construct(string $title = '', string $lang = 'ru')
    {
        parent::__construct('__head__');

        $this->title = $title;
        $this->lang = $lang;

        $child = new TagCollection();

        $charset = (new Tag())
            ->setTagName('meta')
            ->setAttributes([
                'charset' => 'UTF-8'
            ])
        ;

        $child->offsetSet($charset);

        $viewport = (new Tag())
            ->setTagName('meta')
            ->setAttributes([
                'name' => 'viewport',
                'content' => 'width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0',
            ])
        ;

        $httpEquiv = (new Tag())
            ->setTagName('meta')
            ->setAttributes([
                'http-equiv' => 'X-UA-Compatible',
                'content' => 'ie=edge',
            ])
        ;

        $child->offsetSet($httpEquiv);

        $titleTag = (new Tag())
            ->setTagName('title')
            ->setInnerText("<?php print \$title ?>")
            ->setUseCloseTag(true)
        ;

        $child->offsetSet($titleTag);

        $head = (new Tag())
            ->setUseCloseTag(true)
            ->setTagName('head')
            ->setChildCollection($child)
        ;

        $this->tagCollection->offsetSet($head, 'head');
    }

    public function addTag(TagInterface $tag): static
    {
        /** @var TagInterface $tag */
        $head = $this->tagCollection->offsetGet('head');

        $head->getChildCollection()->offsetSet($tag);

        return $this;
    }
}
