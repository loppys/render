<?php

namespace Vengine\Render\Collections;

use Vengine\Render\Interfaces\AssetInterface;

class AssetCollection extends AbstractCollection
{
    protected string $_entityClass = AssetInterface::class;
}
