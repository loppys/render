<?php

namespace Render\Engine;

use Render\Engine\Storage\ConstStorage;
use Render\Engine\Storage\DataStorage;
use Render\Engine\Data\Config;
use Render\Engine\ManagerInterface;
use Vengine\Render\RenderManager;

/**
 * @deprecated
 * @see RenderManager
 */
class DefaultManager implements ManagerInterface
{
    protected string $templateFolder = ConstStorage::TEMPLATE_FOLDER;

    protected array $tpl = [];

    protected array $js = [];

    protected DataStorage $dataStorage;

    private string $dataKey;

    protected array $defaultTemplateList = [];

    private Config $config;

    public function __construct(DataStorage $dataStorage)
    {
        if (empty($this->config)) {
            $this->initConfig();
        }

        $this->dataStorage = $dataStorage;
        $this->templateFolder = $_SERVER['DOCUMENT_ROOT'] . $this->templateFolder;

        $this->initDefaultTemplate();
    }

    public function getDataKey(): string
    {
        $source = implode(' ', $this->tpl);

        $this->dataKey = md5($source);

        return $this->dataKey;
    }

    protected function initConfig(): void
    {
        $this->config = new Config();
    }

    public function getConfig(): Config
    {
        return $this->config;
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

    public function setDefaultTemplatePath(string $name, string $path): static
    {
        $this->defaultTemplateList[$name] = $this->getTemplateFolder() . $path;

        return $this;
    }

    public function setTemplateFolder(string $templateFolder): static
    {
        $this->templateFolder = $_SERVER['DOCUMENT_ROOT'] . $templateFolder;

        return $this;
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
