<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Jira\Api;

use Oqq\Office\Exception\InvalidArgumentException;
use Oqq\Office\Jira\Api\IssueId;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Jira\Api\IssueId
 */
final class IssueIdTest extends TestCase
{
    /**
     * @dataProvider validValueProvider
     */
    public function testItWillCreateFromPerfectValue(string $valueExample): void
    {
        $valueObject = IssueId::fromString($valueExample);

        Assert::assertSame($valueExample, $valueObject->value());
    }

    /**
     * @return iterable<array-key, array{0: string}>
     */
    public function validValueProvider(): iterable
    {
        yield ['1'];
        yield ['2'];
        yield ['100'];
    }

    public function testItThrowsWithInvalidPayload(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a non-empty value. Got: ""');

        IssueId::fromString('');
    }
}
