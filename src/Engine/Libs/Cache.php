<?php

namespace Render\Engine\Libs;

use Render\Engine\Storage\ConstStorage;

class Cache
{
    private mixed $cache;

    private string $cacheFileName;

    private string $cacheFolder;

    private string $cacheFullFileName;

    private string $cacheFullFilePath;

    private int $cacheTime;

    private string $dataName;

    private bool $cacheEnabled = true;

    private static Cache $instance;

    public function __construct(string $dataName)
    {
        if (ConstStorage::CACHE_ENABLED !== true) {
            $this->cacheDisable();
        }

        $this->setCacheTime(ConstStorage::CACHE_TIME);
        $this->cacheFolder = $_SERVER['DOCUMENT_ROOT'] . ConstStorage::CACHE_FOLDER;

        $this->dataName = $dataName;

        $md5hash = md5($dataName);
        $this->cacheFileName = substr($md5hash, 2, 30);

        $this->cacheFullFilePath = $this->cacheFolder . substr($md5hash, 0, 1) . '/' . substr($md5hash, 1, 1) . '/';
        $this->cacheFullFileName = $this->cacheFullFilePath . $this->cacheFileName . '.php';
    }

    public function cacheEnable(): void
    {
        $this->cacheEnabled = true;
    }

    public function cacheDisable(): void
    {
        $this->cacheEnabled = false;
    }

    public function getCacheEnabled(): bool
    {
        return $this->cacheEnabled;
    }

    public function setCacheTime(int $seconds): void
    {
        $this->cacheTime = $seconds;
    }

    public function initCacheData(): bool
    {
        if (!$this->cacheEnabled) {
            return false;
        }

        $cacheOld = time() - @filemtime($this->cacheFullFileName);
        if ($cacheOld < $this->cacheTime) {
            $fp = @fopen($this->cacheFullFileName, "r");
            $this->cache = @fread($fp, filesize($this->cacheFullFileName));
            @fclose($fp);

            return true;
        }

        return false;
    }

    public function getCacheData()
    {
        if (!$this->cacheEnabled || empty($this->cache)) {
            return '';
        }

        $fp = @fopen($this->cacheFullFileName, "r");
        $this->cache = @fread($fp, filesize($this->cacheFullFileName));
        @fclose($fp);

        return $this->cache;
    }

    public function updateCacheData($newData): bool
    {
        if (!$this->cacheEnabled) {
            return false;
        }

        $this->cache = $newData;
        $output = $this->cache;

        if (!@file_exists($this->cacheFullFilePath)) {
            @mkdir($this->cacheFullFilePath, 0777, true);
        }

        $fp = @fopen($this->cacheFullFileName, "w");

        @fwrite($fp, $output);
        @fclose($fp);

        return true;
    }

    public static function clearCache(string $path = ''): bool
    {
        if (empty(self::$instance)) {
            self::$instance = new static('clear_cache');
        }

        $obj = self::$instance;

        if (empty($path)) {
            $path = $obj->cacheFolder;
        }

        if (is_file($path)) {
            return unlink($path);
        }

        if (is_dir($path)) {
            foreach (scandir($path) as $item) {
                if ($item !== '.' && $item !== '..') {
                    $obj::clearCache($path . DIRECTORY_SEPARATOR . $item);
                }
            }

            return rmdir($path);
        }

        return false;
    }

    public function getCacheFileName(): string
    {
        return $this->cacheFileName;
    }

    public function getFilePath(): string
    {
        return $this->cacheFullFileName;
    }

    public function getCacheFolder(): string
    {
        return $this->cacheFolder;
    }

    public function getDataName(): string
    {
        return $this->dataName;
    }
}
