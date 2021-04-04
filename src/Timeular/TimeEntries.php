<?php

declare(strict_types=1);

namespace Oqq\Office\Timeular;

use Generator;
use IteratorAggregate;
use Traversable;
use Oqq\Office\Util\Assert;

/**
 * @implements IteratorAggregate<TimeEntry>
 */
final class TimeEntries implements IteratorAggregate
{
    /** @var array<TimeEntry> */
    private array $values;

    public static function fromArray(array $values): self
    {
        Assert::allIsArray($values);

        $timeEntries = \array_map(
            static fn (array $timeEntry): TimeEntry => TimeEntry::fromArray($timeEntry),
            $values,
        );

        return new self(...$timeEntries);
    }

    public function sortByDate(): self
    {
        $values = $this->values;

        usort($values, static function (TimeEntry $left, TimeEntry $right): int {
            return $left->duration()->date() <=> $right->duration()->date();
        });

        return new self(...$values);
    }

    /**
     * @return Generator<TimeEntry>
     */
    public function getIterator(): Generator
    {
        yield from $this->values;
    }

    private function __construct(TimeEntry ...$values)
    {
        $this->values = $values;
    }
}
