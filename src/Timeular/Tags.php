<?php

declare(strict_types=1);

namespace Oqq\Office\Timeular;

use Generator;
use IteratorAggregate;
use Oqq\Office\Util\Assert;

/**
 * @implements IteratorAggregate<Tag>
 */
final class Tags implements IteratorAggregate
{
    /** @var array<Tag> */
    private array $values;

    public static function fromArray(array $values): self
    {
        Assert::allIsArray($values);

        $tags = \array_map(
            static fn (array $tag): Tag => Tag::fromArray($tag),
            $values,
        );

        return new self(...$tags);
    }

    /**
     * @return Generator<array-key, Tag, mixed, void>
     */
    public function getIterator(): Generator
    {
        yield from $this->values;
    }

    private function __construct(Tag ...$values)
    {
        $this->values = $values;
    }
}
