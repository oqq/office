<?php

declare(strict_types=1);

namespace Oqq\Office\Timeular;

use Oqq\Office\Util\Assert;

final class Note
{
    private string $text;
    private Tags $tags;

    public static function fromArray(array $values): self
    {
        Assert::keyExists($values, 'text');
        Assert::nullOrString($values['text']);

        Assert::keyExists($values, 'tags');
        Assert::isArray($values['tags']);

        $text = $values['text'] ?? '';
        $tags = Tags::fromArray($values['tags']);

        return new self($text, $tags);
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function getFilteredText(): string
    {
        $filter = [];

        foreach ($this->tags as $tag) {
            $filter[] = \sprintf('<{{|t|%s|}}>', $tag->id());
        }

        $filteredText = \str_replace($filter, '', $this->text);
        $filteredText = trim($filteredText);

        return $filteredText;
    }

    private function __construct(string $text, Tags $tags)
    {
        $this->text = $text;
        $this->tags = $tags;
    }
}
