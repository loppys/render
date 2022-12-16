<?php

namespace Render\Engine;

class DefaultConfig
{
    /**
     * @param string $property
     *
     * @return mixed
     */
    public function get(string $property)
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        }

        return '';
    }

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return DefaultConfig
     */
    public function set(string $name, $value): DefaultConfig
    {
        if (property_exists($this, $name)) {
            $this->{$name} = $value;
        }

        return $this;
    }
}