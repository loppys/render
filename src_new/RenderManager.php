<?php

namespace Vengine\Render;

use Vengine\Cache\CacheManager;
use Vengine\Cache\Drivers\TemplateCacheDriver;
use Vengine\Render\Builders\PageBuilder;
use Vengine\Render\Exceptions\RenderException;
use Vengine\Render\Interfaces\BuilderInterface;
use Vengine\Render\Storages\VariableStorage;
use Psr\SimpleCache\InvalidArgumentException;

class RenderManager
{
    protected VariableStorage $variableStorage;

    protected BuilderInterface $builder;

    protected TemplateCacheDriver $cache;

    private int $maxIteration = 1;

    private int $currentIteration = 0;

    public function __construct(?VariableStorage $variableStorage = null)
    {
        $this->variableStorage = $variableStorage ?? new VariableStorage();

        $this->builder = new PageBuilder();
        $this->cache = (new CacheManager())->template;
    }

    public function setMainBuilder(BuilderInterface $builder): static
    {
        $this->builder = $builder;

        return $this;
    }

    /**
     * @throws RenderException
     */
    public function getHtml(): string
    {
        return $this->builder->build()->getHtml();
    }

    /**
     * @throws RenderException|InvalidArgumentException
     */
    public function render(string $title = '', string $lang = 'ru', string $uniqueName = ''): void
    {
        if (!empty($uniqueName)) {
            $this->builder->setDataKey($uniqueName);
        }

        $dataKey = $this->builder->getDataKey() . $title . $lang;

        $_data = [];

        $commentBlock = "<?php /* data-key: {$dataKey} */" . "\n" . "/** \n Default Variables: \n @var array \$_data" . "\n";

        $commentBlock .= "* @var string \$title \n * @var string \$lang \n";

        foreach ($this->variableStorage->getVariables() as $name => $var) {
            if (!is_string($name)) {
                $_data[] = $var;

                continue;
            }

            $type = gettype($var);
            $commentBlock .= "* @var {$type} \${$name} \n";

            $$name = $var;
        }

        $commentBlock .= '*' . "\n" . ' Global Variables:' . "\n";

        foreach ($this->variableStorage->getGlobalVariables() as $gk => $gv) {
            $type = gettype($gv);
            $commentBlock .= "* @var {$type} \${$gk} \n";

            $$gk = $gv;
        }

        $commentBlock .= "*/ \n ?> \n";

        if (!$this->cache->getConfig()->isEnabled()) {
            print $this->getHtml();

            trigger_error(
                'Attention! Cache is disabled, it is recommended to enable it for faster operation.',
                E_USER_WARNING
            );

            return;
        }

        $cachePath = $this->getCachePath($dataKey);
        if (file_exists($cachePath)) {
            ob_start();

            include $cachePath;

            $result = ob_get_contents();

            ob_clean();

            print $result;
        } else {
            $tplPath = $this->compileTemplate($dataKey, $commentBlock);

            if (file_exists($tplPath)) {
                include $tplPath;
            } else {
                print 'compile error.';
            }
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws RenderException
     */
    protected function compileTemplate(
        string $dataKey = '',
        string $commentBlock = ''
    ): string {
        $html = $commentBlock . $this->getHtml();

        if (!empty($html)) {
            if (!$this->cache->set($dataKey, $html)) {
                throw new RenderException('fail cache save.');
            }

            if ($this->currentIteration >= $this->maxIteration) {
                throw new RenderException('Iteration limit exceeded.');
            }

            ++$this->currentIteration;

            return $this->getCachePath($dataKey);
        }

        print 'Empty template';

        return '';
    }

    public function addGlobalVariable(string $name, mixed $value): static
    {
        $this->variableStorage->addGlobalVariable($name, $value);

        return $this;
    }

    public function addVariable(mixed $value, string $name): static
    {
        if (empty($name)) {
            $this->variableStorage->addVariable($value);
        } else {
            $this->variableStorage->setVariableByName($name, $value);
        }

        return $this;
    }

    public function getBuilder(): BuilderInterface
    {
        return $this->builder;
    }

    public function getCache(): TemplateCacheDriver
    {
        return $this->cache;
    }

    protected function getCachePath(string $key): string
    {
        return $this->cache->getPath(
            $this->cache->buildKey($key)
        );
    }
}
