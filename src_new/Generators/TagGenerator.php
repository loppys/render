<?php

namespace Vengine\Render\Generators;

use Vengine\Render\Generators\Entities\TagInfo;
use Vengine\Render\Tags\Tag;

class TagGenerator
{
    public static function create(TagInfo $tagInfo): Tag
    {
        return (new Tag())
            ->setTagName($tagInfo->getTagName())
            ->setAttributes($tagInfo->getAttributes())
            ->setInnerText($tagInfo->getInnerText())
            ->setUseCloseTag($tagInfo->isUseCloseTag())
            ;
    }
}
