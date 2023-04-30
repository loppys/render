<?php

namespace Render\Engine;

use Render\Engine\Data\Config;

interface ManagerInterface
{
    public function getConfig(): Config;

    /**
     * Для корректной работы кэша
     */
    public function getDataKey(): string;
}
