<?php

declare(strict_types=1);

namespace Oqq\Office\Jira\Tempo;

use Oqq\Office\Util\Assertion;

final class IssueId
{
    private int $value;

    public static function fromInt(int $value): self
    {
        Assertion::positiveInteger($value);

        return new self($value);
    }

    public function value(): int
    {
        return $this->value;
    }

    private function __construct(int $value)
    {
        $this->value = $value;
    }
}
