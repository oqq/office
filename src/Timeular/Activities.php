<?php

declare(strict_types=1);

namespace Oqq\Office\Timeular;

use Generator;
use IteratorAggregate;
use Oqq\Office\Exception\RuntimeException;
use Oqq\Office\Util\Assertion;

/**
 * @implements IteratorAggregate<Activity>
 */
final class Activities implements IteratorAggregate
{
    /** @var array<Activity> */
    private array $values;

    public static function fromArray(array $values): self
    {
        Assertion::allIsArray($values);

        $activities = \array_map(
            static fn (array $activity): Activity => Activity::fromArray($activity),
            $values,
        );

        return new self(...$activities);
    }

    public function grabWithId(string $activityId): Activity
    {
        foreach ($this->values as $value) {
            if ($activityId === $value->id()) {
                return $value;
            }
        }

        throw new RuntimeException('Could not find activity with id ' . $activityId);
    }

    /**
     * @return Generator<Activity>
     */
    public function getIterator(): Generator
    {
        yield from $this->values;
    }

    private function __construct(Activity ...$values)
    {
        $this->values = $values;
    }
}
