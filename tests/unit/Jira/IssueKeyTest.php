<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Jira;

use Oqq\Office\Exception\InvalidArgumentException;
use Oqq\Office\Jira\IssueKey;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Jira\IssueKey
 */
final class IssueKeyTest extends TestCase
{
    /**
     * @dataProvider validValueProvider
     */
    public function testItWillCreateFromPerfectValue(string $valueExample): void
    {
        $valueObject = IssueKey::fromString($valueExample);

        Assert::assertSame($valueExample, $valueObject->toString());
    }

    public function validValueProvider(): iterable
    {
        yield ['T-1'];
        yield ['TEST-1000'];
        yield ['TEEEEEEEST-999999'];
    }

    public function testItThrowsWithInvalidPayload(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The value "invalid" does not match the expected pattern');

        IssueKey::fromString('invalid');
    }
}
