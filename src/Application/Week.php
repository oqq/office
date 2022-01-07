<?php

declare(strict_types=1);

namespace Oqq\Office\Application;

use DateTimeImmutable;
use Oqq\Office\Util\Assertion;

final class Week
{
    private string $value;

    public static function fromString(string $value): self
    {
        #Assert::notFalse(
        #    DateTimeImmutable::createFromFormat('Y \WW', $value),
        #    \sprintf('Value "%s" is not a valid week.', $value)
        #);

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
