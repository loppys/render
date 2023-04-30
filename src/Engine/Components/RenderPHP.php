<?php

namespace Render\Engine\Components;

use Render\Engine\Data\Manager;
use Render\Engine\Libs\Cache;
use Render\Engine\RenderInterface;

class RenderPHP implements RenderInterface
{
    /**
     * @var array
     */
    protected $variable;

    /**
     * @var Manager
     */
    private $manager;

    /**
     * @var Cache
     */
    private $cache;

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

        if ($this->cache->initCacheData()) {
            $cacheTemplate = $this->cache->getCacheData();

            if (!empty($cacheTemplate)) {
                include $this->cache->getFilePath();

                $tpl = ob_get_contents();
            }
        } else {
            $tmpFile = $this->getCompileTemplate($lang);

            include $tmpFile;

            $tpl = ob_get_contents();

            unlink($tmpFile);
        }

        ob_clean();

        print $tpl;
    }

    protected function getCompileTemplate(string $lang = 'ru'): string
    {
        $config = $this->manager->getConfig();

        $tplFolder = $this->manager->getTemplateFolder();
        $tempFile = $tplFolder . $this->manager->getDataKey() . '.php';

        $headerPath = $this->manager->getDefaultTemplatePath('header');
        $footerPath = $this->manager->getDefaultTemplatePath('footer');

        $this->addHtml('<!DOCTYPE html>', $tempFile);
        $this->addHtml('<html lang="'. $lang .'">', $tempFile);

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

        $this->cache->updateCacheData(file_get_contents($tempFile));

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
}

