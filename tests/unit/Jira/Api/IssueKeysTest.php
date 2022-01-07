<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Jira\Api;

use Oqq\Office\Exception\AssertionFailedException;
use Oqq\Office\Jira\Api\IssueKey;
use Oqq\Office\Jira\Api\IssueKeys;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Jira\Api\IssueKeys
 */
final class IssueKeysTest extends TestCase
{
    /**
     * @dataProvider validPayloadProvider
     */
    public function testItWillCreateFromPerfectPayload(array $payloadExample): void
    {
        $valueObject = IssueKeys::fromArray($payloadExample);

        Assert::assertCount(\count($payloadExample), $valueObject);
        Assert::assertContainsOnly(IssueKey::class, $valueObject);
        Assert::assertSame($payloadExample, $valueObject->toArray());
    }

    /**
     * @return iterable<string, array{0: array}>
     */
    public function validPayloadProvider(): iterable
    {
        yield 'empty list' => [
            [],
        ];

        yield 'one value' => [
            [
                PayloadExample::issueKey(),
            ],
        ];

        yield 'two values' => [
            [
                PayloadExample::issueKey(),
                PayloadExample::issueKey(),
            ],
        ];
    }

    /**
     * @dataProvider invalidPayloadProvider
     */
    public function testItThrowsWithInvalidPayload(array $payloadExample, \Exception $expectedException): void
    {
        $this->expectExceptionObject($expectedException);

        IssueKeys::fromArray($payloadExample);
    }

    /**
     * @return iterable<array-key, array{0: array, 1: \Exception}>
     */
    public function invalidPayloadProvider(): iterable
    {
        yield 'invalid type' => [
            [5],
            new AssertionFailedException('Expected a string. Got: integer'),
        ];
    }
}
