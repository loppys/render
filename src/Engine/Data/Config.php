<?php

namespace Render\Engine\Data;

use Render\Engine\DefaultConfig;

class Config extends DefaultConfig
{
    /**
     * @var bool
     */
    protected $ignoreHeader = false;

    /**
     * @var bool
     */
    protected $ignoreFooter = false;

    /**
     * @var bool
     */
    protected $dontSaveCache = false;
}