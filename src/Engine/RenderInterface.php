<?php

namespace Render\Engine;

use Render\Engine\Data\Manager;
use Render\Engine\Libs\Cache;

interface RenderInterface
{
    public function init(Manager $manager, Cache $cache): RenderInterface;

    public function render(): void;

    public function getTitle(): string;
}
