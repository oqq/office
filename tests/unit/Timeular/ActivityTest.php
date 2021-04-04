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
        ]);

        Assert::assertSame('1', $valueObject->id());
        Assert::assertSame('test', $valueObject->name());
        Assert::assertSame('#fff', $valueObject->color());
    }

    /**
     * @dataProvider invalidPayloadProvider
     */
    public function testItThrowsWithInvalidPayload(\Exception $expectedException, array $payloadExample): void
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
        ];

        yield from ValueObjectPayloadAssertion::nonEmptyString($perfectValues, 'id');
        yield from ValueObjectPayloadAssertion::nonEmptyString($perfectValues, 'name');
        yield from ValueObjectPayloadAssertion::nonEmptyString($perfectValues, 'color');
    }
}
