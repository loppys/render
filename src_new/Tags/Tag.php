<?php

namespace Vengine\Render\Tags;

use Vengine\Render\Collections\TagCollection;
use Vengine\Render\Interfaces\TagInterface;

class Tag implements TagInterface
{
    protected string $uniqueName = '';

    protected string $tagName = '';

    protected array $attributes = [];

    protected ?TagCollection $childTagCollection = null;

    protected string $html = '';

    protected string $innerText = '';

    protected bool $useCloseTag = true;

    protected array $onlyOpenTag = [
        'meta',
        'input'
    ];

    public function __construct(
        string $tagName = '',
        string $innerText = '',
        array $attributes = [],
        bool $useCloseTag = true,
        string $uniqueName = ''
    ) {
        if (empty($uniqueName)) {
            $uniqueName = uniqid('tag_', false);
        }

        if (in_array($tagName, $this->onlyOpenTag, true)) {
            $useCloseTag = false;
        }

        if ($tagName === '') {
            $useCloseTag = false;
        }

        $this
            ->setUniqueName($uniqueName)
            ->setTagName($tagName)
            ->setInnerText($innerText)
            ->setAttributes($attributes)
            ->setUseCloseTag($useCloseTag)
        ;
    }

    public function getTagName(): string
    {
        return $this->tagName;
    }

    public function setTagName(string $name): static
    {
        $this->tagName = $name;

        return $this;
    }

    public function getUniqueName(): string
    {
        return $this->uniqueName;
    }

    public function setUniqueName(string $uniqueName): static
    {
        $this->uniqueName = $uniqueName;

        return $this;
    }

    public function getChildCollection(): ?TagCollection
    {
        return $this->childTagCollection;
    }

    public function setChildCollection(TagCollection $tagCollection): static
    {
        $this->childTagCollection = $tagCollection;

        return $this;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes): static
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function setUseCloseTag(bool $use): static
    {
        $this->useCloseTag = $use;

        return $this;
    }

    public function isUseCloseTag(): bool
    {
        return $this->useCloseTag;
    }

    public function setInnerText(string $innerText): static
    {
        $this->innerText = $innerText;

        return $this;
    }

    public function getInnerText(): string
    {
        return $this->innerText;
    }

    public function getHtml(array $dynamicData = [], $rebuild = false): string
    {
        if ($this->html && !$rebuild) {
            return $dynamicData ? vsprintf($this->html, $dynamicData) : $this->html;
        }

        $this->html = $this->getRecursiveHtml($this);

        return $dynamicData ? vsprintf($this->html, $dynamicData) : $this->html;
    }

    protected function getRecursiveHtml(Tag $tag): string
    {
        $innerHtml = "";

        if (!empty($tag->childTagCollection)) {
            /** @var Tag $child */
            foreach ($tag->childTagCollection as $child) {
                $innerHtml .= $tag->getRecursiveHtml($child);
            }
        }

        $name = $tag->getTagName();
        $attributes = $tag->getStringHtmlAttributes($tag);
        $innerText = $tag->getInnerText();

        $closeTag = '';
        if ($tag->isUseCloseTag()) {
            $closeTag = "</{$name}>";
        }

        if ($attributes !== '') {
            $attributes = ' ' . $attributes;
        } else {
            $allowTag = [
                'div',
                'input',
                'span',
                'button',
                'label'
            ];

            if (in_array($this->getTagName(), $allowTag, true)) {
                $attributes = " id='{$this->getUniqueName()}'";
            }
        }

        if ($innerText !== '') {
            $innerText = "\n{$innerText}";
        }

        if ($innerHtml !== '') {
            $innerHtml = "\n{$innerHtml}";
        }

        $openTag = '';
        if ($name !== '') {
            $openTag = "<{$name}{$attributes}>";
        }

        return "{$openTag}{$innerText}{$innerHtml}{$closeTag}\n";
    }

    public function setHtml(string $html): static
    {
        $this->html = $html;

        return $this;
    }

    protected function getStringHtmlAttributes(Tag $tag): string
    {
        return implode(' ', array_map(
            static function ($value, $key) {
                return isset($value) ? sprintf('%s="%s"', $key, $value) : sprintf('%s', $key);
            },
            $tag->getAttributes(),
            array_keys($tag->getAttributes())
        ));
    }
}
