<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Util;

use DateTimeZone;
use Oqq\Office\Exception\InvalidArgumentException;
use Oqq\Office\Util\DateTime;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Util\DateTime
 */
final class DateTimeTest extends TestCase
{
    public function testItCreatesFromString(): void
    {
        Assert::assertSame(
            '2020-10-01',
            DateTime::fromString('2020-10-01', 'Y-m-d')->format('Y-m-d')
        );
    }

    public function test_it_creates_from_string_with_timezone(): void
    {
        Assert::assertSame(
            '2020-12-01T10:00:00+01:00',
            DateTime::fromString('2020-12-01 10:00:00', 'Y-m-d H:i:s', new DateTimeZone('Europe/Berlin'))->format(\DATE_ATOM)
        );
    }

    public function test_it_throws_with_invalid_format_from_string(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Date "2020-12-01" is invalid or does not match format "Z"');

        DateTime::fromString('2020-12-01', 'Z');
    }
}
