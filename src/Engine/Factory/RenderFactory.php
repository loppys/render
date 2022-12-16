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

    /**
     * @var Manager
     */
    private $manager;

    /**
     * @var string
     */
    private $renderClass;

    /**
     * @var Cache
     */
    private $cache;

    public function __construct(Manager $manager, string $renderClass = '')
    {
        $this->manager = $manager;

        $this->cache = new Cache($this->getDataKey());

        if ($this->manager->getConfig()->get('dontSaveCache')) {
            Cache::clearCache();
        }

        if (!class_exists($renderClass) || empty($renderClass)) {
            $this->renderClass = self::DEFAULT_RENDER;
        } else {
            $this->renderClass = $renderClass;
        }
    }

    public function render(): void
    {
        try {
            $obj = new $this->renderClass();

            if ($obj instanceof RenderInterface) {
                $obj->init($this->manager, $this->cache)->render();
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
        $this->cache = new Cache($this->getDataKey());

        $this->render();
    }

    public function runTemplate(string $tpl): void
    {
        $this->manager->setTemplate($tpl);
        $this->cache = new Cache($this->getDataKey());

        $this->render();
    }

    protected function getDataKey(): string
    {
        return $this->manager->getDataKey();
    }
}