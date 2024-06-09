<?php

namespace Render\Engine\Components;

use Render\Engine\Data\Manager;
use Render\Engine\Libs\Cache;
use Render\Engine\RenderInterface;

class RenderPHP implements RenderInterface
{
    protected array $variable;

    private Manager $manager;

    private Cache $cache;

    public function init(Manager $manager, Cache $cache): RenderInterface
    {
        $this->manager = $manager;
        $this->cache = $cache;
        $this->variable = $manager->getVariableList();

        return $this;
    }

    public function render(string $lang = 'ru'): void
    {
        $tpl = '';

        foreach ($this->variable as $key => $value) {
            $$key = $value;
        }

        ob_start();

        $cachePath = $this->cache->getPath($this->manager->getDataKey());

        if (file_exists($cachePath)) {
            include $cachePath;
        } else {
            include $this->getCompileTemplate($lang);
        }

        $result = ob_get_contents();

        ob_clean();

        print $result;
    }

    protected function getCompileTemplate(string $lang = 'ru'): string
    {
        $config = $this->manager->getConfig();

        $tplFolder = $this->manager->getTemplateFolder();
        $tempFile = $this->cache->getPath($this->manager->getDataKey());

        $headerPath = $this->manager->getDefaultTemplatePath('header');
        $footerPath = $this->manager->getDefaultTemplatePath('footer');

        $this->addHtml('<!DOCTYPE html>', $tempFile);
        $this->addHtml('<html lang="<?php print $lang ?>">', $tempFile);

        $this->addHtml($this->manager->getHead(), $tempFile);

        $this->addHtml('<body>', $tempFile);

        if (!$config->get('ignoreHeader')) {
            $this->connectTemplate($headerPath, $tempFile);
        }

        foreach ($this->manager->getTemplateList() as $template) {
            $this->connectTemplate($tplFolder . $template, $tempFile);
        }

        if (!$config->get('ignoreFooter')) {
            $this->connectTemplate($footerPath, $tempFile);
        }

        foreach ($this->manager->getJsList() as $info) {
            if ($info['skipPage']) {
                $this->addHtml('<script type="text/javascript">' . $info['script'] . '</script>' . PHP_EOL, $tempFile);
            }
        }

        $this->addHtml('</body>', $tempFile);
        $this->addHtml('</html>', $tempFile);

        return $tempFile;
    }

    protected function connectTemplate(string $path, string $savePath): void
    {
        if (file_exists($path)) {
            file_put_contents($savePath, file_get_contents($path), FILE_APPEND);
        }
    }

    protected function addHtml(string $html, string $savePath = '', bool $print = false, bool $return = true): string
    {
        if (!empty($savePath)) {
            file_put_contents($savePath, $html . PHP_EOL, FILE_APPEND);
        }

        if ($print) {
            print $html;
        }

        if ($return) {
            return $html;
        }

        return '';
    }

    public function getTitle(): string
    {
        return $this->manager->getTitle();
    }
}

