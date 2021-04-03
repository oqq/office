<?php

declare(strict_types=1);

namespace Oqq\Office\Util;

use DateTimeZone;
use DateTimeImmutable;
use Oqq\Office\Exception\InvalidArgumentException;

final class DateTime
{
    public static function fromString(string $value, string $format, ?DateTimeZone $timeZone = null): DateTimeImmutable
    {
        $dateTime = DateTimeImmutable::createFromFormat($format, $value, $timeZone);

        if (false === $dateTime) {
            throw new InvalidArgumentException(
                \sprintf(
                    'Date "%s" is invalid or does not match format "%s".',
                    $value,
                    $format
                )
            );
        }

        return $dateTime;
    }
}
