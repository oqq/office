<?php

declare(strict_types=1);

namespace Oqq\Office\Jira\Tempo;

use Generator;
use IteratorAggregate;
use Oqq\Office\Jira\Tempo\Worklog;
use Oqq\Office\Util\Assertion;

/**
 * @implements IteratorAggregate<Worklog>
 */
final class Worklogs implements IteratorAggregate
{
    /** @var array<Worklog> */
    private array $values;

    public static function fromArray(array $values): self
    {
        Assertion::allIsArray($values);

        $worklogs = \array_map(
            static fn (array $worklog): Worklog => Worklog::fromArray($worklog),
            $values,
        );

        return new self(...$worklogs);
    }

    /**
     * @return Generator<Worklog>
     */
    public function getIterator(): Generator
    {
        yield from $this->values;
    }

    private function __construct(Worklog ...$values)
    {
        $this->values = $values;
    }
}
