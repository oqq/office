<?php

declare(strict_types=1);

namespace Oqq\Office\Service\Banking;

use Oqq\Office\Util\Assertion;

final class Transactions
{
    /** @var array<Transaction> */
    private readonly array $values;

    public static function fromArray(array $payload): self
    {
        Assertion::allIsArray($payload);

        $values = \array_map(
            static fn (array $value): Transaction => Transaction::fromArray($value),
            $payload,
        );

        return new self(...$values);
    }

    public function toArray(): array
    {
        return \array_map(
            static fn (Transaction $value): array => $value->toArray(),
            $this->values,
        );
    }

    private function __construct(Transaction ...$values)
    {
        $this->values = $values;
    }
}
