<?php

namespace Vengine\Render\Storages;

use Vengine\Render\Exceptions\RenderException;
use Vengine\Render\RenderManager;
use Vengine\Render\Tags\MsgTag;
use Vengine\Render\Traits\SingletonTrait;

class MessageBuffer
{
    use SingletonTrait;

    /**
     * @var array<string>
     */
    protected array $buffer = [];

    private string $tmpPath = '';

    public function __construct()
    {
        $this->tmpPath = $_SERVER['DOCUMENT_ROOT'] . '/_tmp/buffer';

        if (!is_dir($this->tmpPath)) {
            mkdir($this->tmpPath);
        }

        if ($dir = opendir($this->tmpPath)) {
            while (false !== ($entry = readdir($dir))) {
                if (in_array($entry, ['.', '..'])) {
                    continue;
                }

                $this->buffer["buffer::{$entry}"] = file_get_contents($this->tmpPath . '/' . $entry);
            }
        }
    }

    /**
     * @see Only text. php code will be displayed as text
     */
    public function addMessage(string $message = '', string $key = '', bool $createTag = true): static
    {
        if (empty($key)) {
            $key = sha1($message);
        }

        $key = "buffer::{$key}";

        if (!empty($this->buffer[$key])) {
            $tKey = $key;
            while (true) {
                $tKey = 'buffer::' . sha1($tKey);

                if (empty($this->buffer[$tKey])) {
                    $key = $tKey;

                    break;
                }
            }
        }

        $this->buffer[$key] = $message;

        if ($createTag) {
            RenderManager::getInstance()
                ->getBuilder()
                ->addTag(
                    (new MsgTag())->setHtml($key . "\n")
                )
            ;
        } else {
            file_put_contents(
                $this->tmpPath . '/' . str_replace('buffer::', '', $this->getLastKey()), trim($message)
            );
        }

        return $this;
    }

    public function getLastKey(): string
    {
        return array_key_last($this->buffer);
    }

    public function removeMessage(string $key): static
    {
        if (file_exists($this->tmpPath . '/' . $key)) {
            unlink($this->tmpPath . '/' . $key);
        }

        unset($this->buffer["buffer::{$key}"]);

        return $this;
    }

    public function getMessage(string $key): string
    {
        return $this->buffer[$key] ?? $this->buffer["buffer::{$key}"] ?? '';
    }

    public function searchKey(string $message): string
    {
        return array_search($message, $this->buffer, true);
    }

    public function replaceMessages(string $buffer): string
    {
        return str_replace(array_keys($this->buffer), $this->buffer, $buffer);
    }

    public function getTmpPath(): string
    {
        return $this->tmpPath;
    }
}
