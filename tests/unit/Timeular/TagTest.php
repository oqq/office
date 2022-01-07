<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Timeular;

use Oqq\Office\Test\ValueObjectPayloadAssertion;
use Oqq\Office\Timeular\Tag;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Timeular\Tag
 */
final class TagTest extends TestCase
{
    public function testItWillCreateFromPerfectPayload(): void
    {
        $valueObject = Tag::fromArray([
            'id' =>  1,
            'label' => 'test',
        ]);

        Assert::assertSame(1, $valueObject->id());
        Assert::assertSame('test', $valueObject->label());
    }

    /**
     * @dataProvider invalidPayloadProvider
     */
    public function testItThrowsWithInvalidPayload(array $payloadExample, \Exception $expectedException): void
    {
        $this->expectExceptionObject($expectedException);

        Tag::fromArray($payloadExample);
    }

    /**
     * @return iterable<string, array{0: \Exception, 1: array}>
     */
    public function invalidPayloadProvider(): iterable
    {
        $perfectValues = [
            'id' =>  1,
            'label' => 'test',
        ];

        yield from ValueObjectPayloadAssertion::positiveInteger($perfectValues, 'id');
        yield from ValueObjectPayloadAssertion::string($perfectValues, 'label');
    }
}
