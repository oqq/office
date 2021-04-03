<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Timeular;

use Oqq\Office\Test\ValueObjectPayloadAssertion;
use Oqq\Office\Timeular\TimeEntry;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Timeular\TimeEntry
 */
final class TimeEntryTest extends TestCase
{
    public function testItWillCreateFromPerfectPayload(): void
    {
        $valueObject = TimeEntry::fromArray([
            'id' =>  '1',
            'activityId' => '2',
            'note' => PayloadExample::note(),
            'duration' => PayloadExample::duration(),
        ]);

        Assert::assertSame('2', $valueObject->activityId());

        $valueObject->note();
        $valueObject->duration();
    }

    /**
     * @dataProvider invalidPayloadProvider
     */
    public function testItThrowsWithInvalidPayload(\Exception $expectedException, array $payloadExample): void
    {
        $this->expectExceptionObject($expectedException);

        TimeEntry::fromArray($payloadExample);
    }

    /**
     * @return iterable<array-key, array{0: \Exception, 1: array}>
     */
    public function invalidPayloadProvider(): iterable
    {
        $perfectValues = [
            'id' =>  '1',
            'activityId' => '2',
            'note' => PayloadExample::note(),
            'duration' => PayloadExample::duration(),
        ];

        yield from ValueObjectPayloadAssertion::nonEmptyString($perfectValues, 'id');
        yield from ValueObjectPayloadAssertion::nonEmptyString($perfectValues, 'activityId');
        yield from ValueObjectPayloadAssertion::array($perfectValues, 'note');
        yield from ValueObjectPayloadAssertion::array($perfectValues, 'duration');
    }
}
