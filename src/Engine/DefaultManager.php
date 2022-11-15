<?php

namespace Render\Engine;

use Render\Engine\Storage\ConstStorage;
use Render\Engine\Storage\DataStorage;

class DefaultManager
{
    protected $templateFolder = ConstStorage::TEMPLATE_FOLDER;

    protected $tpl = [];

    protected $js = [];

    /**
     * @var DataStorage
     */
    protected $dataStorage;

    /**
     * @var string
     */
    private $dataKey;

    /**
     * @var array
     */
    protected $defaultTemplateList;

    public function __construct(DataStorage $dataStorage)
    {
        $this->dataStorage = $dataStorage;
        $this->templateFolder = $_SERVER['DOCUMENT_ROOT'] . $this->templateFolder;

        $this->initDefaultTemplate();
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

    protected function initDefaultTemplate(): void
    {
        $pathList = [
            '404' => '404.html',
            '500' => '500.html',
            'header' => 'header.html',
            'footer' => 'footer.html',
        ];

        foreach ($pathList as $name => $path) {
            $this->setDefaultTemplatePath($name, $path);
        }
    }

    public function setDefaultTemplatePath(string $name, string $path): void
    {
        $this->defaultTemplateList[$name] = $this->getTemplateFolder() . $path;
    }

    public function getVariableList(): array
    {
        return $this->dataStorage->getVariableList();
    }

    public function getDefaultTemplatePath(string $name): string
    {
        return $this->defaultTemplateList[$name] ?: '';
    }

    public function getTemplateList(): array
    {
        return $this->tpl;
    }

    public function getTemplateFolder(): string
    {
        return $this->templateFolder;
    }

    public function getJsList(): array
    {
        return $this->js;
    }
}