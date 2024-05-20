<?php

namespace Render\Engine\Data;

use Render\Engine\DefaultManager;
use Vengine\Render\RenderManager;

/**
 * @deprecated
 * @see RenderManager
 */
class Manager extends DefaultManager
{
    protected array $style = [];

    protected array $meta = [];

    protected string $title = ':)';

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
    <title> <?php print \$this->getTitle() ?> </title>
    {$meta}
    {$jsPath}
    {$style}
    {$jsScript}
</head>
HTML;
    }

    public function addMetaData(string $name, string $value, string $content): static
    {
        $this->meta[] = '<meta ' . $name . '="' . $value . '" content="' . $content . '">';

        return $this;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function addTemplate(string $path): static
    {
        if (!array_key_exists($path, $this->tpl)) {
            $this->tpl[] = $path;
        }

        return $this;
    }

    public function addTemplateList(array $pathList): static
    {
        $this->tpl = array_merge($this->tpl, $pathList);

        return $this;
    }

    public function setTemplateList(array $pathList, bool $merge = true): static
    {
        if ($merge) {
            $this->tpl = array_merge($this->tpl, $pathList);
        } else {
            $this->tpl = $pathList;
        }

        return $this;
    }

    public function setTemplate(string $path): static
    {
        $this->tpl = [$path];

        return $this;
    }

    /**
     * skipPage - добавить скрипт в конец страницы
     */
    public function initJsByName(string $name, string $script, bool $skipPage = false): static
    {
        if (empty($this->js[$name])) {
            $this->js[$name] = [
                'script' => $script,
                'skipPage' => $skipPage
            ];
        }

        return $this;
    }

    public function initJs(string $script, bool $skipPage = false): static
    {
        $this->js[] = [
            'script' => $script,
            'skipPage' => $skipPage
        ];

        return $this;
    }

    public function initJsPath(string $path): static
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

    public function addStyle(string $path): static
    {
        $this->style[] = '<link rel="stylesheet" href="' . $path . '">';

        return $this;
    }
}
