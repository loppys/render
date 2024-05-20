<?php

namespace Vengine\Render\Generators\Entities;

use Vengine\Render\Generators\Storage\DuplicatePrefixStorage;
use Vengine\Render\Generators\Storage\SortMethodStorage;

class MapGenerateOptions
{
    /*
     * 0 - без ограничений
     * Отвечает за максимальную глубину генерации
     */
    protected int $deep = 0;

    /*
     * Для добавления своего метода сортировки требуется указать одноименнный метод в классе AssetMap
     * (либо же добавить callback с указанием объекта и его метода)
     * В метод будет передан в качестве параметра AssetMap и метод должен возвращать этот же параметр
     * Пример использования можно посмотреть на примере стандартного метода сортировки AssetMap::defaultSort
     */
    protected string $sortMethod = SortMethodStorage::DEFAULT;

    /*
     * 0 - без ограничений
     * Отвечает за то, сколько будет сгенерировано однотипных тэгов (учитываются только абсолютно идентичные)
     */
    protected int $tagLimit = 0;

    /*
     * Сколько требуется раз продублировать информацию о теге
     */
    protected int $duplicates = 0;

    /*
     * Доступные методы генерации префикса (Все остальные префиксы будут считаться обычным текстом):
     * {iterable} - автоматически будет проставляться номер текущей итерации
     * {random} - случайное число от 0-9999999 (не рекомендуется использовать, если дубликатов нужно много)
     * {unique} - Уникальный id
     *
     * Префикс для дубликата будет прописан:
     * - В имени дубликата
     * - В атрибуте name (если используется, иначе не будет проставлен)
     * - В атрибуете id (если используется, иначе не будет проставлен)
     */
    protected string $duplicatePrefix = DuplicatePrefixStorage::ITERABLE;

    protected string $afterCallback = 'cache';

    public function getDeep(): int
    {
        return $this->deep;
    }

    public function setDeep(int $deep): static
    {
        $this->deep = $deep;

        return $this;
    }

    public function getSortMethod(): string
    {
        return $this->sortMethod;
    }

    public function setSortMethod(string $sortMethod): static
    {
        $this->sortMethod = $sortMethod;

        return $this;
    }

    public function getTagLimit(): int
    {
        return $this->tagLimit;
    }

    public function setTagLimit(int $tagLimit): static
    {
        $this->tagLimit = $tagLimit;

        return $this;
    }

    public function getDuplicates(): int
    {
        return $this->duplicates;
    }

    public function setDuplicates(int $duplicates): static
    {
        $this->duplicates = $duplicates;

        return $this;
    }

    public function getDuplicatePrefix(): string
    {
        return $this->duplicatePrefix;
    }

    public function setDuplicatePrefix(string $duplicatePrefix): static
    {
        $this->duplicatePrefix = $duplicatePrefix;

        return $this;
    }

    public function getAfterCallback(): string
    {
        return $this->afterCallback;
    }

    public function setAfterCallback(string $afterCallback): static
    {
        $this->afterCallback = $afterCallback;

        return $this;
    }

    public function cache(): static
    {
        return $this;
    }
}
