<?php

declare(strict_types=1);

namespace Oqq\Office\Timeular;

use Generator;
use IteratorAggregate;
use Oqq\Office\Util\Assertion;

/**
 * @implements IteratorAggregate<TimeEntry>
 */
final class TimeEntries implements IteratorAggregate
{
    /** @var array<TimeEntry> */
    private array $values;

    public static function fromArray(array $values): self
    {
        Assertion::allIsArray($values);

        $timeEntries = \array_map(
            static fn (array $timeEntry): TimeEntry => TimeEntry::fromArray($timeEntry),
            $values,
        );

        return new self(...$timeEntries);
    }

    public function sortedByDate(): self
    {
        $values = $this->values;

        usort($values, static function (TimeEntry $left, TimeEntry $right): int {
            return $left->duration()->date() <=> $right->duration()->date();
        });

        return new self(...$values);
    }

    public function filter(callable $filterFunction): self
    {
        return new self(...\array_filter($this->values, $filterFunction));
    }

    /**
     * @return Generator<string, TimeEntries>
     */
    public function perWeek(): Generator
    {
        $entriesByWeek = [];

        /** @var TimeEntry $timeEntry */
        foreach ($this->sortedByDate() as $timeEntry) {
            $entriesByWeek[$timeEntry->duration()->date()->format('Y \WW')][] = $timeEntry;
        }

        foreach ($entriesByWeek as $week => $values) {
            yield $week => new self(...$values);
        }
    }

    public function timeSpentSeconds(): int
    {
        $timeSpentSeconds = 0;

        foreach ($this->values as $timeEntry) {
            $timeSpentSeconds += $timeEntry->duration()->timeSpentSeconds();
        }

        return $timeSpentSeconds;
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
