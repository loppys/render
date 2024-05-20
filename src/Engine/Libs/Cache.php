<?php

namespace Render\Engine\Libs;

use Vengine\Cache\CacheManager;
use Vengine\Cache\Drivers\TemplateCacheDriver;

/**
 * @deprecated
 * @see use `vengine/cache` or other cache libs
 */
class Cache
{
    protected TemplateCacheDriver $cacheDriver;

    public function __construct()
    {
        $this->cacheDriver = (new CacheManager())->template;
    }

    public function set(string $name, mixed $value): bool
    {
        return $this->cacheDriver->set($name, $value);
    }

    public function get(string $name): mixed
    {
        return $this->cacheDriver->get($name);
    }

    public function getPath(string $key): string
    {
        return $this->cacheDriver->getPath(
            $this->cacheDriver->buildKey($key)
        );
    }
}
