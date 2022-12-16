<?php

namespace Render\Engine\Data;

use Render\Engine\DefaultManager;

class Manager extends DefaultManager
{
    protected $style = [];

    protected $meta = [];

    protected $title = ':)';

    public function getHead(): string
    {
        $meta = implode(PHP_EOL, $this->meta);
        $style = implode(PHP_EOL, $this->style);

        $jsScript = [];
        $jsPath = [];

        foreach ($this->getJsList() as $info) {
            if (!$info['skipPage'] && !empty($info['script'])) {
                $jsScript[] = '<script type="text/javascript">' . $info['script'] . '</script>' . PHP_EOL;
            }

            if (!empty($info['path'])) {
                $jsPath[] = '<script src="' . $info['path'] . '"></script>' . PHP_EOL;
            }
        }

        $jsScript = implode(PHP_EOL, $jsScript);
        $jsPath = implode(PHP_EOL, $jsPath);

        return <<<HTML
<head>
    <title> {$this->getTitle()} </title>
    {$meta}
    {$jsPath}
    {$style}
    {$jsScript}
</head>
HTML;
    }

    public function addMetaData(string $name, string $value): self
    {
        $this->meta[] = '<meta ' . $name . '="' . $value . '">';

        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
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

    public function setTemplateList(array $pathList, bool $merge = true): self
    {
        if ($merge) {
            $this->tpl = array_merge($this->tpl, $pathList);
        } else {
            $this->tpl = $pathList;
        }

        return $this;
    }

    public function setTemplate(string $path): self
    {
        $this->tpl = [$path];

        return $this;
    }

    /**
     * @param string $name
     * @param string $script
     *
     * @param bool $skipPage - добавить скрипт в конец страницы
     *
     * @return Manager
     */
    public function initJsByName(string $name, string $script, bool $skipPage = false): self
    {
        if (empty($this->js[$name])) {
            $this->js[$name] = [
                'script' => $script,
                'skipPage' => $skipPage
            ];
        }

        return $this;
    }

    public function initJs(string $script, bool $skipPage = false): self
    {
        $this->js[] = [
            'script' => $script,
            'skipPage' => $skipPage
        ];

        return $this;
    }

    public function initJsPath(string $path): self
    {
        $this->js[] = [
            'path' => $path
        ];

        return $this;
    }

    public function getJsInfoByName(string $name): array
    {
        return $this->js[$name] ?: [];
    }

    public function addStyle(string $path): self
    {
        $this->style[] = '<link rel="stylesheet" href="' . $path . '">';

        return $this;
    }
}
