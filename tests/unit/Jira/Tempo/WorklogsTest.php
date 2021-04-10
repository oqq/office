<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Jira\Tempo;

use Oqq\Office\Exception\InvalidArgumentException;
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
    public function testItThrowsWithInvalidPayload(\Exception $expectedException, array $payloadExample): void
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
            new InvalidArgumentException('Expected an array. Got: integer'),
            [5],
        ];
    }
}