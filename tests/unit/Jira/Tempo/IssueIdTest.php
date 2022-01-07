<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Jira\Tempo;

use Oqq\Office\Exception\AssertionFailedException;
use Oqq\Office\Jira\Tempo\IssueId;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Jira\Tempo\IssueId
 */
final class IssueIdTest extends TestCase
{
    /**
     * @dataProvider validValueProvider
     */
    public function testItWillCreateFromPerfectValue(int $valueExample): void
    {
        $valueObject = IssueId::fromInt($valueExample);

        Assert::assertSame($valueExample, $valueObject->value());
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
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage('Expected a positive integer. Got: 0');

        IssueId::fromInt(0);
    }
}
