<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Jira;

use Oqq\Office\Exception\AssertionFailedException;
use Oqq\Office\Jira\JiraUser;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Jira\JiraUser
 */
final class JiraUserTest extends TestCase
{
    /**
     * @dataProvider validValueProvider
     */
    public function testItWillCreateFromPerfectValue(string $valueExample): void
    {
        $valueObject = JiraUser::fromString($valueExample);

        Assert::assertSame($valueExample, $valueObject->toString());
    }

    /**
     * @return iterable<array-key, array{0: string}>
     */
    public function validValueProvider(): iterable
    {
        yield ['max'];
        yield ['max.mustermann'];
    }

    public function testItThrowsWithInvalidPayload(): void
    {
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage('Expected a non-empty value. Got: ""');

        JiraUser::fromString('');
    }
}
