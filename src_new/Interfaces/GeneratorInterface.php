<?php

namespace Vengine\Render\Interfaces;

use Vengine\Render\Generators\Entities\Map;
use Vengine\Render\Generators\Entities\Result;

interface GeneratorInterface
{
    public function generate(?Map $map): Result;
}
