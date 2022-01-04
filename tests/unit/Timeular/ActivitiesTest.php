<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Timeular;

use Oqq\Office\Exception\InvalidArgumentException;
use Oqq\Office\Exception\RuntimeException;
use Oqq\Office\Timeular\Activity;
use Oqq\Office\Timeular\Activities;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Timeular\Activities
 */
final class ActivitiesTest extends TestCase
{
    /**
     * @dataProvider validPayloadProvider
     */
    public function testItWillCreateFromPerfectPayload(array $payloadExample): void
    {
        $valueObject = Activities::fromArray($payloadExample);

        Assert::assertCount(\count($payloadExample), $valueObject);
        Assert::assertContainsOnly(Activity::class, $valueObject);
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
                PayloadExample::activity(),
            ],
        ];

        yield 'two values' => [
            [
                PayloadExample::activity(),
                PayloadExample::activity(),
            ],
        ];
    }

    /**
     * @dataProvider invalidPayloadProvider
     */
    public function testItThrowsWithInvalidPayload(\Exception $expectedException, array $payloadExample): void
    {
        $this->expectExceptionObject($expectedException);

        Activities::fromArray($payloadExample);
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

    public function testItWillFindActivityById(): void
    {
        $valueObject = Activities::fromArray([
            [
                'id' => '1',
                'name' => 'activity alpha',
                'color' => '#fff',
                'spaceId' => '2',
            ],
            [
                'id' => '2',
                'name' => 'activity beta',
                'color' => '#fff',
                'spaceId' => '2',
            ],
            [
                'id' => '3',
                'name' => 'activity gamma',
                'color' => '#fff',
                'spaceId' => '2',
            ],
        ]);

        $result = $valueObject->grabWithId('2');

        Assert::assertSame('activity beta', $result->name());
    }

    public function testItThrowsIfActivityByIdNotFound(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Could not find activity with id 2');

        $valueObject = Activities::fromArray([]);
        $valueObject->grabWithId('2');
    }
}
