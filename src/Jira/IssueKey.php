<?php

declare(strict_types=1);

namespace Oqq\Office\Jira;

use Oqq\Office\Util\Assert;

final class IssueKey
{
    public const PATTERN = '/^[A-Z]{1,10}-\d+$/';

    private string $value;

    public static function fromString(string $value): self
    {
        Assert::regex($value, self::PATTERN);

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
