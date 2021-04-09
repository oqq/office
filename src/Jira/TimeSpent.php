<?php

declare(strict_types=1);

namespace Oqq\Office\Jira;

use Oqq\Office\Util\Assert;

final class TimeSpent
{
    private int $seconds;

    public static function fromSeconds(int $seconds): self
    {
        Assert::positiveInteger($seconds);

        return new self($seconds);
    }

    public function seconds(): int
    {
        return $this->seconds;
    }

    private function __construct(int $seconds)
    {
        $this->seconds = $seconds;
    }
}
