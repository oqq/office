<?php

declare(strict_types=1);

namespace Oqq\Office\Jira\Tempo;

use Oqq\Office\Util\Assertion;

final class WorklogId
{
    private int $value;

    public static function fromInt(int $value): self
    {
        Assertion::positiveInteger($value);

        return new self($value);
    }

    public function toString(): string
    {
        return (string) $this->value;
    }

    private function __construct(int $value)
    {
        $this->value = $value;
    }
}
