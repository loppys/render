<?php

namespace Vengine\Render\Helpers;

use Vengine\Render\Generators\Entities\MapGenerate;

class AssetMapHelper
{
    public static function createMap(): MapGenerate
    {
        $map = new MapGenerate();

        return $map;
    }
}
