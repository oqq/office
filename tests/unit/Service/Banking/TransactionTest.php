<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Service\Banking;

use Exception;
use Oqq\Office\Service\Banking\Transaction;
use Oqq\Office\Test\ValueObjectPayloadAssertion;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Service\Banking\Transaction
 */
final class TransactionTest extends TestCase
{
    /**
     * @dataProvider getValidPayloadExamples
     */
    public function test_it_creates_from_payload(array $payload): void
    {
        $valueObject = Transaction::fromPayload($payload);

        Assert::assertSame($payload, $valueObject->toArray());
    }

    /**
     * @return iterable<array-key, array{0: array}>
     */
    public function getValidPayloadExamples(): iterable
    {
        yield [
            [
                'date' => 'some value',
                'name' => 'some value',
                'description' => 'some value',
                'amount' => 2.00,
            ],
        ];
    }

    /**
     * @dataProvider getInvalidPayloadExamples
     */
    public function test_it_throws_with_invalid_payload(array $payload, Exception $expectedException): void
    {
        $this->expectExceptionObject($expectedException);

        Transaction::fromPayload($payload);
    }

    /**
     * @return iterable<array-key, array{0: array, 1: Exception}>
     */
    public function getInvalidPayloadExamples(): iterable
    {
        $perfectValues = [
            'date' => 'some value',
            'name' => 'some value',
            'description' => 'some value',
            'amount' => 2.00,
        ];

        yield from ValueObjectPayloadAssertion::notEmptyString($perfectValues, 'date');
        yield from ValueObjectPayloadAssertion::string($perfectValues, 'name');
        yield from ValueObjectPayloadAssertion::string($perfectValues, 'description');
        yield from ValueObjectPayloadAssertion::float($perfectValues, 'amount');
    }
}
