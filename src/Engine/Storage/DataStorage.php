<?php

namespace Render\Engine\Storage;

use Render\Engine\DefaultStorage;

class DataStorage extends DefaultStorage
{
    public const DEFAULT_DATA_NAME = '_data';

    public function setVariableByName(string $name, $value): self
    {
        $this->set($name, $value);

        return $this;
    }

    public function addVariable($value): self
    {
        $this->data[self::DEFAULT_DATA_NAME][] = $value;

        return $this;
    }

    public function getVariableByName(string $name)
    {
        return $this->getDataByName($name);
    }

    public function getVariableList(): array
    {
        return $this->getDataList();
    }
}
