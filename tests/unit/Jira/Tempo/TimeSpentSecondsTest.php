<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Jira\Tempo;

use Oqq\Office\Exception\InvalidArgumentException;
use Oqq\Office\Jira\Tempo\TimeSpentSeconds;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Jira\Tempo\TimeSpentSeconds
 */
final class TimeSpentSecondsTest extends TestCase
{
    /**
     * @dataProvider validValueProvider
     */
    public function testItWillCreateFromPerfectValue(int $valueExample): void
    {
        $valueObject = TimeSpentSeconds::fromInteger($valueExample);

        Assert::assertSame($valueExample, $valueObject->value());
    }

    /**
     * @return iterable<array-key, array{0: int}>
     */
    public function validValueProvider(): iterable
    {
        yield [1];
        yield [60];
    }

    public function testItThrowsWithInvalidPayload(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a non-negative integer. Got: -1');

        TimeSpentSeconds::fromInteger(-1);
    }
}
