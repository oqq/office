<?php

declare(strict_types=1);

namespace Oqq\Office\Timeular;

use Generator;
use IteratorAggregate;
use Oqq\Office\Util\Assertion;

/**
 * @implements IteratorAggregate<Tag>
 */
final class Tags implements IteratorAggregate
{
    /** @var array<Tag> */
    private array $values;

    public static function fromArray(array $values): self
    {
        Assertion::allIsArray($values);

        $tags = \array_map(
            static fn (array $tag): Tag => Tag::fromArray($tag),
            $values,
        );

        return new self(...$tags);
    }

    /**
     * @return Generator<Tag>
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
