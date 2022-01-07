<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Timeular;

use Oqq\Office\Test\ValueObjectPayloadAssertion;
use Oqq\Office\Timeular\Activity;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Timeular\Activity
 */
final class ActivityTest extends TestCase
{
    public function testItWillCreateFromPerfectPayload(): void
    {
        $valueObject = Activity::fromArray([
            'id' =>  '1',
            'name' => 'test',
            'color' => '#fff',
            'spaceId' => '2',
        ]);

        Assert::assertSame('1', $valueObject->id());
        Assert::assertSame('test', $valueObject->name());
        Assert::assertSame('#fff', $valueObject->color());
        Assert::assertSame('2', $valueObject->spaceId());
    }

    /**
     * @dataProvider invalidPayloadProvider
     */
    public function testItThrowsWithInvalidPayload(array $payloadExample, \Exception $expectedException): void
    {
        $this->expectExceptionObject($expectedException);

        Activity::fromArray($payloadExample);
    }

    /**
     * @return iterable<string, array{0: \Exception, 1: array}>
     */
    public function invalidPayloadProvider(): iterable
    {
        $perfectValues = [
            'id' =>  '1',
            'name' => 'test',
            'color' => '#fff',
            'spaceId' => '2',
        ];

        yield from ValueObjectPayloadAssertion::notEmptyString($perfectValues, 'id');
        yield from ValueObjectPayloadAssertion::notEmptyString($perfectValues, 'name');
        yield from ValueObjectPayloadAssertion::notEmptyString($perfectValues, 'color');
        yield from ValueObjectPayloadAssertion::notEmptyString($perfectValues, 'spaceId');
    }
}
