<?php

declare(strict_types=1);

namespace Oqq\Office\Jira\Tempo;

use Oqq\Office\Util\Assert;

final class WorklogId
{
    private int $value;

    public static function fromInt(int $value): self
    {
        Assert::positiveInteger($value);

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
