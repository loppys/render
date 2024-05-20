<?php

namespace Vengine\Render\Generators;

use Vengine\Render\Generators\Entities\Map;
use Vengine\Render\Generators\Entities\MapGenerateOptions;
use Vengine\Render\Generators\Entities\TagInfo;
use Exception;

class MapGenerator
{
    protected MapGenerateOptions $mapOptions;

    protected Map $map;

    public function __construct(?MapGenerateOptions $mapOptions = null)
    {
        $this->mapOptions = $mapOptions ?? new MapGenerateOptions();

        $this->newMap();
    }

    /**
     * @param array<TagInfo> $info
     *
     * @throws Exception
     */
    public function create(array $info = []): Map
    {
        if (empty($info) && $this->map->getTagInfoCollection()->count() === 0) {
            return $this->map;
        }

        $i = 0;
        $deep = $this->mapOptions->getDeep();
        $duplicateCount = $this->mapOptions->getDuplicates();

        foreach ($info as $key => $tagInfo) {
            if (!$tagInfo instanceof TagInfo) {
                unset($info[$key]);

                continue;
            }

            if ($deep !== 0 && $i >= $deep) {
                break;
            }

            $this->map->getTagInfoCollection()->offsetSet($tagInfo, $tagInfo->getName());

            if ($duplicateCount > 0) {
                $tagInfo->setClones($duplicateCount);

                for ($d = 0; $d < $duplicateCount; $d++) {
                    $clone = clone $tagInfo;

                    $cloneName = $this->mapOptions->getDuplicatePrefix();
                    $duplicateName = match ($cloneName) {
                        '{iterable}' => $d . '_' . $clone->getName(),
                        '{random}' => random_int(0, 9999999) . '_' . $clone->getName(),
                        '{unique}' => uniqid('unique:', true) . '_' . $clone->getName(),
                        default => $cloneName . $clone->getName(),
                    };

                    $tagInfo->addCloneName($duplicateName);

                    $this->map->getTagInfoCollection()->offsetSet($clone, $duplicateName);
                }
            }

            $i++;
        }

        $sortMethod = $this->mapOptions->getSortMethod();
        if (!empty($sortMethod) && method_exists($this->map, $sortMethod)) {
            $this->map->{$sortMethod}();
        }

        $afterCallback = $this->mapOptions->getAfterCallback();
        if (!empty($afterCallback) && method_exists($this->map, $afterCallback)) {
            $this->map->{$afterCallback}();
        }

        return $this->map;
    }

    public function addTagInfo(TagInfo $tagInfo): static
    {
        $this->map->getTagInfoCollection()->offsetSet($tagInfo, $tagInfo->getName());

        return $this;
    }

    public function getMapOptions(): MapGenerateOptions
    {
        return $this->mapOptions;
    }

    public function setMapOptions(MapGenerateOptions $mapOptions): static
    {
        $this->mapOptions = $mapOptions;

        return $this;
    }

    public function newMap(): Map
    {
        return $this->map = new Map();
    }
}
