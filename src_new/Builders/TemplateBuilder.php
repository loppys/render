<?php

namespace Vengine\Render\Builders;

use Vengine\Render\Bundles\TemplateBundle;
use Vengine\Render\Interfaces\BundleInterface;

class TemplateBuilder extends AbstractBuilder
{
    public function build(): BundleInterface
    {
        return new TemplateBundle();
    }
}