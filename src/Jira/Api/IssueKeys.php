<?php

declare(strict_types=1);

namespace Oqq\Office\Jira\Api;

use Generator;
use IteratorAggregate;
use Oqq\Office\Jira\Api\IssueKey;
use Oqq\Office\Util\Assert;

/**
 * @implements IteratorAggregate<IssueKey>
 */
final class IssueKeys implements IteratorAggregate
{
    /** @var array<IssueKey> */
    private array $values;

    public static function fromArray(array $values): self
    {
        Assert::allString($values);

        $issueKeys = \array_map(
            static fn (string $issueKey): IssueKey => IssueKey::fromString($issueKey),
            $values,
        );

        return new self(...$issueKeys);
    }

    /**
     * @return array<string>
     */
    public function toArray(): array
    {
        return \array_map(
            static fn (IssueKey $issueKey): string => $issueKey->toString(),
            $this->values,
        );
    }

    /**
     * @return Generator<IssueKey>
     */
    public function getIterator(): Generator
    {
        yield from $this->values;
    }

    private function __construct(IssueKey ...$values)
    {
        $this->values = $values;
    }
}
