<?php

namespace Vengine\Render\Generators;

use Vengine\Render\Generators\Entities\Map;
use Vengine\Render\Generators\MapGenerator;
use Vengine\Render\Generators\Entities\Result;
use Vengine\Render\Interfaces\GeneratorInterface;

abstract class AbstractGenerator implements GeneratorInterface
{
    protected Result $result;

    protected MapGenerator $mapGenerate;

    public function __construct(?MapGenerator $mapGenerate = null)
    {
        $this->result = new Result();
        $this->mapGenerate = $mapGenerate ?? new MapGenerator();
    }

    public function getResult(): Result
    {
        return $this->result;
    }

    abstract public function generate(?Map $map): Result;
}
