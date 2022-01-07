<?php

declare(strict_types=1);

namespace Oqq\Office\Application;

use DateTimeImmutable;
use Oqq\Office\Util\Assertion;

final class Month
{
    private string $value;

    public static function fromString(string $value): self
    {
        Assertion::notFalse(DateTimeImmutable::createFromFormat('Y-m', $value));

        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    private function __construct(string $value)
    {
        $this->value = $value;
    }
}
