<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Jira;

use Oqq\Office\Exception\InvalidArgumentException;
use Oqq\Office\Jira\Issue;
use Oqq\Office\Jira\Issues;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Jira\Issues
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
    public function testItThrowsWithInvalidPayload(\Exception $expectedException, array $payloadExample): void
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
            new InvalidArgumentException('Expected an array. Got: integer'),
            [5],
        ];
    }
}
