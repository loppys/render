<?php

namespace Vengine\Render\Bundles;

use Vengine\Render\Exceptions\RenderException;

class TemplateBundle extends AbstractBundle
{
    protected string $path = '';

    protected string $templateFolder = '';

    protected bool $pathAbsolute = false;

    public function __construct(string $path = '', bool $absolute = false)
    {
        $this->path = $path;
        $this->pathAbsolute = $absolute;

        parent::__construct();
    }

    /**
     * @throws RenderException
     */
    public function getHtml(): string
    {
        if (empty($this->path)) {
            return '';
        }

        if (!$this->isPathAbsolute()) {
            $ds = DIRECTORY_SEPARATOR;
            $this->path = $_SERVER['DOCUMENT_ROOT'] . $ds . $this->templateFolder . $ds . $this->path;
        }

        if (!file_exists($this->path)) {
            throw new RenderException('template not found.');
        }

        return file_get_contents($this->path);
    }

    public function setTemplateFolder(string $templateFolder): static
    {
        $this->templateFolder = $templateFolder;

        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function isPathAbsolute(): bool
    {
        return $this->pathAbsolute;
    }

    public function setPathAbsolute(bool $pathAbsolute): static
    {
        $this->pathAbsolute = $pathAbsolute;

        return $this;
    }
}
