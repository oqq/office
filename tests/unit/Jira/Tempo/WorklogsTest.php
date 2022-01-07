<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Jira\Tempo;

use Oqq\Office\Exception\AssertionFailedException;
use Oqq\Office\Jira\Tempo\Worklog;
use Oqq\Office\Jira\Tempo\Worklogs;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Jira\Tempo\Worklogs
 */
final class WorklogsTest extends TestCase
{
    /**
     * @dataProvider validPayloadProvider
     */
    public function testItWillCreateFromPerfectPayload(array $payloadExample): void
    {
        $valueObject = Worklogs::fromArray($payloadExample);

        Assert::assertCount(\count($payloadExample), $valueObject);
        Assert::assertContainsOnly(Worklog::class, $valueObject);
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
                PayloadExample::worklog(),
            ],
        ];

        yield 'two values' => [
            [
                PayloadExample::worklog(),
                PayloadExample::worklog(),
            ],
        ];
    }

    /**
     * @dataProvider invalidPayloadProvider
     */
    public function testItThrowsWithInvalidPayload(array $payloadExample, \Exception $expectedException): void
    {
        $this->expectExceptionObject($expectedException);

        Worklogs::fromArray($payloadExample);
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
