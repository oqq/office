<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Timeular;

use Oqq\Office\Test\ValueObjectPayloadAssertion;
use Oqq\Office\Timeular\Note;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Timeular\Note
 */
final class NoteTest extends TestCase
{
    public function testItWillCreateFromPerfectPayload(): void
    {
        $valueObject = Note::fromArray([
            'text' =>  '<{{|t|1|}}> foo bar ',
            'tags' => PayloadExample::tags(),
        ]);

        Assert::assertCount(\count(PayloadExample::tags()), $valueObject->tags());
        Assert::assertSame('foo bar', $valueObject->getFilteredText());
    }

    /**
     * @dataProvider invalidPayloadProvider
     */
    public function testItThrowsWithInvalidPayload(array $payloadExample, \Exception $expectedException): void
    {
        $this->expectExceptionObject($expectedException);

        Note::fromArray($payloadExample);
    }

    /**
     * @return iterable<string, array{0: \Exception, 1: array}>
     */
    public function invalidPayloadProvider(): iterable
    {
        $perfectValues = [
            'text' =>  'test',
            'tags' => PayloadExample::tags(),
        ];

        yield from ValueObjectPayloadAssertion::string($perfectValues, 'text');
        yield from ValueObjectPayloadAssertion::array($perfectValues, 'tags');
    }
}
