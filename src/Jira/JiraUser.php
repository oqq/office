<?php

declare(strict_types=1);

namespace Oqq\Office\Jira;

use Oqq\Office\Util\Assertion;

final class JiraUser
{
    private string $value;

    public static function fromString(string $value): self
    {
        Assertion::notEmpty($value);

        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }

    private function __construct(string $value)
    {
        $this->value = $value;
    }
}
