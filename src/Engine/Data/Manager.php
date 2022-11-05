<?php

namespace Render\Engine\Data;

use Render\Engine\Storage\ConstStorage;
use Render\Engine\Storage\DataStorage;

class Manager
{
    private $templateFolder = ConstStorage::TEMPLATE_FOLDER;

    protected $tpl = [];

    protected $js = [];

    /**
     * @var string
     */
    private $dataKey;

    /**
     * @var DataStorage
     */
    private $dataStorage;

    public function __construct(DataStorage $dataStorage)
    {
        $this->dataStorage = $dataStorage;

        $this->templateFolder = $_SERVER['DOCUMENT_ROOT'] . $this->templateFolder;
    }

    public function addTemplate(string $path): self
    {
        if (!array_key_exists($path, $this->tpl)) {
            $this->tpl[] = $path;
        }

        return $this;
    }

    public function addTemplateList(array $pathList): self
    {
        $this->tpl = array_merge($this->tpl, $pathList);

        return $this;
    }

    public function getTemplateList(): array
    {
        return $this->tpl;
    }

    public function getDataKey(): string
    {
        if (!empty($this->dataKey)) {
            return $this->dataKey;
        }

        $source = implode(' ', $this->tpl);

        $this->dataKey = md5($source);

        return $this->dataKey;
    }

    public function setTemplateList(array $pathList, bool $merge = true): self
    {
        if ($merge) {
            $this->tpl = array_merge($this->tpl, $pathList);
        } else {
            $this->tpl = $pathList;
        }

        return $this;
    }

    public function getVariableList(): array
    {
        return $this->dataStorage->getVariableList();
    }

    public function getTemplateFolder(): string
    {
        return $this->templateFolder;
    }
}