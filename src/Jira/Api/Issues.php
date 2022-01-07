<?php

declare(strict_types=1);

namespace Oqq\Office\Jira\Api;

use Generator;
use IteratorAggregate;
use Oqq\Office\Jira\Api\Issue;
use Oqq\Office\Util\Assertion;

/**
 * @implements IteratorAggregate<Issue>
 */
final class Issues implements IteratorAggregate
{
    /** @var array<Issue> */
    private array $values;

    public static function fromArray(array $values): self
    {
        Assertion::allIsArray($values);

        $issues = \array_map(
            static fn (array $issue): Issue => Issue::fromArray($issue),
            $values,
        );

        return new self(...$issues);
    }

    /**
     * @return Generator<Issue>
     */
    public function getIterator(): Generator
    {
        yield from $this->values;
    }

    private function __construct(Issue ...$values)
    {
        $this->values = $values;
    }
}
