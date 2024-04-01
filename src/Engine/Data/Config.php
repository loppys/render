<?php

namespace Render\Engine\Data;

use Render\Engine\DefaultConfig;

class Config extends DefaultConfig
{
    protected bool $ignoreHeader = false;

    protected bool $ignoreFooter = false;

    protected bool $dontSaveCache = false;
}
