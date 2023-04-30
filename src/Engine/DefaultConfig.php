<?php

namespace Render\Engine;

class DefaultConfig
{
    public function get(string $property): mixed
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        }

        return '';
    }

    public function set(string $name, mixed $value): DefaultConfig
    {
        if (property_exists($this, $name)) {
            $this->{$name} = $value;
        }

        return $this;
    }
}
