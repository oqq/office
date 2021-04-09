<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Jira;

use Oqq\Office\Exception\InvalidArgumentException;
use Oqq\Office\Jira\TimeSpent;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Jira\TimeSpent
 */
final class TimeSpentTest extends TestCase
{
    /**
     * @dataProvider validValueProvider
     */
    public function testItWillCreateFromPerfectValue(int $valueExample): void
    {
        $valueObject = TimeSpent::fromSeconds($valueExample);

        Assert::assertSame($valueExample, $valueObject->seconds());
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
        $this->expectExceptionMessage('Expected a positive integer. Got: 0');

        TimeSpent::fromSeconds(0);
    }
}
