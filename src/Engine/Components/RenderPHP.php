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

    public function render(): void
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
            $tmpFile = $this->getCompileTemplate();

            include $tmpFile;

            $tpl = ob_get_contents();

            unlink($tmpFile);
        }

        ob_clean();

        print $tpl;
    }

    protected function getCompileTemplate(): string
    {
        $tplFolder = $this->manager->getTemplateFolder();

        $tempFile = $tplFolder . $this->manager->getDataKey() . '.php';

        foreach ($this->manager->getTemplateList() as $template) {
            $tpl = $tplFolder . $template;

            if (file_exists($tpl)) {
                file_put_contents($tempFile, file_get_contents($tpl), FILE_APPEND);
            }
        }

        $this->cache->updateCacheData(file_get_contents($tempFile));

        return $tempFile;
    }
}

