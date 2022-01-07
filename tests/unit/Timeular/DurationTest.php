<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Timeular;

use Oqq\Office\Test\ValueObjectPayloadAssertion;
use Oqq\Office\Timeular\Duration;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Timeular\Duration
 */
final class DurationTest extends TestCase
{
    public function testItWillCreateDate(): void
    {
        $valueObject = Duration::fromArray([
            'startedAt' =>  '2021-01-01T10:00:00.000',
            'stoppedAt' => '2021-01-01T11:00:00.000',
        ]);

        Assert::assertSame('2021-01-01T00:00:00.000000', $valueObject->date()->format('Y-m-d\TH:i:s.u'));
    }

    /**
     * @dataProvider timeSpentExampleProvider
     */
    public function testItWillCreateTimeSpent(string $expectedTimeSpent, array $values): void
    {
        $valueObject = Duration::fromArray($values);

        Assert::assertSame($expectedTimeSpent, $valueObject->timeSpent());
    }

    /**
     * @return iterable<array-key, array{0: string, 1: array}>
     */
    public function timeSpentExampleProvider(): iterable
    {
        yield [
            '0h 01m',
            [
                'startedAt' =>  '2021-01-01T10:00:00.000',
                'stoppedAt' => '2021-01-01T10:01:00.000',
            ],
        ];

        yield [
            '0h 30m',
            [
                'startedAt' =>  '2021-01-01T10:00:00.000',
                'stoppedAt' => '2021-01-01T10:30:00.000',
            ],
        ];

        yield [
            '1h 10m',
            [
                'startedAt' =>  '2021-01-01T10:10:00.000',
                'stoppedAt' => '2021-01-01T11:20:00.000',
            ],
        ];
    }

    public function testItWillCreateTimeSpentSeconds(): void
    {
        $valueObject = Duration::fromArray([
            'startedAt' =>  '2021-01-01T10:00:00.000',
            'stoppedAt' => '2021-01-01T12:10:00.000',
        ]);

        Assert::assertSame(7800, $valueObject->timeSpentSeconds());
    }

    /**
     * @dataProvider invalidPayloadProvider
     */
    public function testItThrowsWithInvalidPayload(array $payloadExample, \Exception $expectedException): void
    {
        $this->expectExceptionObject($expectedException);

        Duration::fromArray($payloadExample);
    }

    /**
     * @return iterable<string, array{0: \Exception, 1: array}>
     */
    public function invalidPayloadProvider(): iterable
    {
        $perfectValues = [
            'startedAt' =>  '2021-01-01T10:00:00.000',
            'stoppedAt' => '2021-01-01T11:00:00.000',
        ];

        yield from ValueObjectPayloadAssertion::string($perfectValues, 'startedAt');
        yield from ValueObjectPayloadAssertion::string($perfectValues, 'stoppedAt');
    }
}
