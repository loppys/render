<?php

namespace Vengine\Render\Generators;

use Vengine\Render\Assets\Asset;
use Vengine\Render\Generators\Entities\Map;
use Vengine\Render\Generators\Entities\Result;
use Vengine\Render\Generators\Entities\TagInfo;

class AssetGenerator extends AbstractGenerator
{
    public function generate(?Map $map): Result
    {
        if ($map === null) {
            return $this->result;
        }

        $asset = new Asset();

        /** @var TagInfo $value */
        foreach ($map->getTagInfoCollection() as $uniqueName => $info) {
            $tag = TagGenerator::create($info)->setUniqueName($uniqueName);

            $asset->addTag($tag);
        }

        return $this->result->addAsset($asset);
    }
}
