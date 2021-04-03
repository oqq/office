<?php

declare(strict_types=1);

namespace Oqq\Office\Timeular;

use DateTimeImmutable;
use Oqq\Office\Util\Assert;
use Oqq\Office\Util\DateTime;

final class Duration
{
    private const MINUTES_PER_HOUR = 60;
    private const SECONDS_PER_MINUTE = 60;

    private DateTimeImmutable $startedAt;
    private DateTimeImmutable $stoppedAt;

    public static function fromArray(array $values): self
    {
        Assert::keyExists($values, 'startedAt');
        Assert::string($values['startedAt']);

        Assert::keyExists($values, 'stoppedAt');
        Assert::string($values['stoppedAt']);

        $startedAt = DateTime::fromString($values['startedAt'], TimeularApi::DATETIME_FORMAT);
        $stoppedAt = DateTime::fromString($values['stoppedAt'], TimeularApi::DATETIME_FORMAT);

        return new self($startedAt, $stoppedAt);
    }

    public function date(): DateTimeImmutable
    {
        return DateTime::fromString($this->startedAt->format('Y-m-d'), 'Y-m-d|');
    }

    public function timeSpent(): string
    {
        $timeSpentMinutes = $this->timeSpentMinutes();

        return \sprintf(
            '%dh %02dm',
            \floor($timeSpentMinutes / self::MINUTES_PER_HOUR),
            $timeSpentMinutes % self::SECONDS_PER_MINUTE
        );
    }

    public function timeSpentSeconds(): int
    {
        return $this->timeSpentMinutes() * self::SECONDS_PER_MINUTE;
    }

    private function __construct(DateTimeImmutable $startedAt, DateTimeImmutable $stoppedAt)
    {
        $this->startedAt = $startedAt;
        $this->stoppedAt = $stoppedAt;
    }

    private function timeSpentMinutes(): int
    {
        $timeSpent = $this->startedAt->diff($this->stoppedAt);
        $timeSpentMinutes = ($timeSpent->h * self::MINUTES_PER_HOUR) + $timeSpent->i;

        return $timeSpentMinutes;
    }
}
