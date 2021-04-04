<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Timeular;

use Oqq\Office\Exception\InvalidArgumentException;
use Oqq\Office\Timeular\Tag;
use Oqq\Office\Timeular\Tags;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Timeular\Tags
 */
final class TagsTest extends TestCase
{
    /**
     * @dataProvider validPayloadProvider
     */
    public function testItWillCreateFromPerfectPayload(array $payloadExample): void
    {
        $valueObject = Tags::fromArray($payloadExample);

        Assert::assertCount(\count($payloadExample), $valueObject);
        Assert::assertContainsOnly(Tag::class, $valueObject);
    }

    /**
     * @return iterable<array-key, array{0: array}>
     */
    public function validPayloadProvider(): iterable
    {
        yield 'empty list' => [
            [],
        ];

        yield 'one value' => [
            [
                PayloadExample::tag(),
            ],
        ];

        yield 'two values' => [
            [
                PayloadExample::tag(),
                PayloadExample::tag(),
            ],
        ];
    }

    /**
     * @dataProvider invalidPayloadProvider
     */
    public function testItThrowsWithInvalidPayload(\Exception $expectedException, array $payloadExample): void
    {
        $this->expectExceptionObject($expectedException);

        Tags::fromArray($payloadExample);
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
