<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Jira\Tempo;

use Oqq\Office\Exception\InvalidArgumentException;
use Oqq\Office\Jira\Tempo\WorklogId;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Jira\Tempo\WorklogId
 */
final class WorklogIdTest extends TestCase
{
    /**
     * @dataProvider validValueProvider
     */
    public function testItWillCreateFromPerfectValue(int $valueExample): void
    {
        $valueObject = WorklogId::fromInt($valueExample);

        Assert::assertSame((string) $valueExample, $valueObject->toString());
    }

    /**
     * @return iterable<array-key, array{0: int}>
     */
    public function validValueProvider(): iterable
    {
        yield [1];
        yield [2];
        yield [100];
    }

    public function testItThrowsWithInvalidPayload(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a positive integer. Got: 0');

        WorklogId::fromInt(0);
    }
}
