<?php

declare(strict_types=1);

namespace Oqq\Office\Jira\Api;

use Oqq\Office\Util\Assertion;

final class IssueId
{
    private string $value;

    public static function fromString(string $value): self
    {
        Assertion::notEmpty($value);

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
