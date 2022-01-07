<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Jira\Api;

use Oqq\Office\Exception\AssertionFailedException;
use Oqq\Office\Jira\Api\Issue;
use Oqq\Office\Jira\Api\Issues;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Jira\Api\Issues
 */
final class IssuesTest extends TestCase
{
    /**
     * @dataProvider validPayloadProvider
     */
    public function testItWillCreateFromPerfectPayload(array $payloadExample): void
    {
        $valueObject = Issues::fromArray($payloadExample);

        Assert::assertCount(\count($payloadExample), $valueObject);
        Assert::assertContainsOnly(Issue::class, $valueObject);
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
                PayloadExample::issue(),
            ],
        ];

        yield 'two values' => [
            [
                PayloadExample::issue(),
                PayloadExample::issue(),
            ],
        ];
    }

    /**
     * @dataProvider invalidPayloadProvider
     */
    public function testItThrowsWithInvalidPayload(array $payloadExample, \Exception $expectedException): void
    {
        $this->expectExceptionObject($expectedException);

        Issues::fromArray($payloadExample);
    }

    /**
     * @return iterable<string, array{0: \Exception, 1: array}>
     */
    public function invalidPayloadProvider(): iterable
    {
        yield 'invalid type' => [
            [5],
            new AssertionFailedException('Expected an array. Got: integer'),
        ];
    }
}
