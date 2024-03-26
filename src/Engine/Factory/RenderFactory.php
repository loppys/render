<?php

namespace Render\Engine\Factory;

use Render\Engine\Components\RenderPHP;
use Render\Engine\Components\RenderSmarty;
use Render\Engine\Data\Manager;
use Render\Engine\Helpers\ErrorHelper;
use Render\Engine\RenderInterface;
use Render\Engine\Libs\Cache;
use RuntimeException;

class RenderFactory
{
    public const DEFAULT_RENDER = RenderPHP::class;
    public const SMARTY_RENDER = RenderSmarty::class;

    private Manager $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    public function render(string $renderClass = ''): void
    {
        if (!class_exists($renderClass) || empty($renderClass)) {
            $renderClass = self::DEFAULT_RENDER;
        }

        $cache = new Cache($this->getDataKey());

        if ($this->manager->getConfig()->get('dontSaveCache')) {
            Cache::clearCache();
        }

        try {
            $obj = new $renderClass();

            if ($obj instanceof RenderInterface) {
                $obj->init($this->manager, $cache)->render();
            } else {
                ErrorHelper::runException(666);
            }
        } catch (RuntimeException $e) {
            http_response_code($e->getCode());
            print $e->getMessage();
        }
    }

    public function runTemplateList(array $tpl): void
    {
        $this->manager->setTemplateList($tpl, false);

        $this->render();
    }

    public function runTemplate(string $tpl): void
    {
        $this->manager->setTemplate($tpl);

        $this->render();
    }

    protected function getDataKey(): string
    {
        return $this->manager->getDataKey();
    }
}
