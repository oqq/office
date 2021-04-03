<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Timeular;

use Oqq\Office\Exception\InvalidArgumentException;
use Oqq\Office\Timeular\TimeEntries;
use Oqq\Office\Timeular\TimeEntry;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Timeular\TimeEntries
 */
final class TimeEntriesTest extends TestCase
{
    /**
     * @dataProvider validPayloadProvider
     */
    public function testItWillCreateFromPerfectPayload(array $payloadExample): void
    {
        $valueObject = TimeEntries::fromArray($payloadExample);

        Assert::assertCount(\count($payloadExample), $valueObject);
        Assert::assertContainsOnly(TimeEntry::class, $valueObject);
    }

    /**
     * @return iterable<array-key, array<array>>
     */
    public function validPayloadProvider(): iterable
    {
        yield 'empty list' => [
            [],
        ];

        yield 'one value' => [
            [
                PayloadExample::timeEntry(),
            ],
        ];

        yield 'two values' => [
            [
                PayloadExample::timeEntry(),
                PayloadExample::timeEntry(),
            ],
        ];
    }

    /**
     * @dataProvider invalidPayloadProvider
     */
    public function testItThrowsWithInvalidPayload(\Exception $expectedException, array $payloadExample): void
    {
        $this->expectExceptionObject($expectedException);

        TimeEntries::fromArray($payloadExample);
    }

    /**
     * @return iterable<array-key, array{0: \Exception, 1: array}>
     */
    public function invalidPayloadProvider(): iterable
    {
        yield 'invalid type' => [
            new InvalidArgumentException('Expected an array. Got: integer'),
            [5],
        ];
    }

    public function testItSortsEntriesByDate(): void
    {
        $valueObject = TimeEntries::fromArray([
            [
                'id' => '1',
                'activityId' => '1',
                'note' => PayloadExample::note(),
                'duration' => [
                    'startedAt' =>  '2021-01-02T10:00:00.000',
                    'stoppedAt' => '2021-01-02T10:01:00.000',
                ],
            ],
            [
                'id' => '2',
                'activityId' => '2',
                'note' => PayloadExample::note(),
                'duration' => [
                    'startedAt' =>  '2021-01-03T10:00:00.000',
                    'stoppedAt' => '2021-01-03T10:01:00.000',
                ],
            ],
            [
                'id' => '3',
                'activityId' => '3',
                'note' => PayloadExample::note(),
                'duration' => [
                    'startedAt' =>  '2021-01-01T10:00:00.000',
                    'stoppedAt' => '2021-01-01T10:01:00.000',
                ],
            ],
        ]);

        /** @var array<TimeEntry> $sortedEntries */
        $sortedEntries = [...$valueObject->sortByDate()];

        Assert::assertSame('3', $sortedEntries[0]->activityId());
        Assert::assertSame('1', $sortedEntries[1]->activityId());
        Assert::assertSame('2', $sortedEntries[2]->activityId());
    }
}
