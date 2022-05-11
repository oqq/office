<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Service\Banking;

use Exception;
use Oqq\Office\Service\Banking\Transaction;
use Oqq\Office\Test\ValueObjectPayloadAssertion;
use Oqq\Office\Test\ValueObjectPayloadExample;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Service\Banking\Transaction
 */
final class TransactionTest extends TestCase
{
    /**
     * @dataProvider getValidValueExamples
     */
    public function test_it_creates_from_values(array $values): void
    {
        $valueObject = Transaction::fromArray($values);

        Assert::assertSame($values, $valueObject->toArray());

        Assert::assertSame($values['date'], $valueObject->date());
        Assert::assertSame($values['name'], $valueObject->name());
        Assert::assertSame($values['description'], $valueObject->description());
        Assert::assertSame($values['amount'], $valueObject->amount());
    }

    /**
     * @return iterable<array-key, array{0: array}>
     */
    public function getValidValueExamples(): iterable
    {
        yield [ValueObjectPayloadExample::transaction()];

        yield [
            [
                'date' => 'some',
                'name' => '',
                'description' => '',
                'amount' => 1.0,
            ],
        ];
    }

    /**
     * @dataProvider getInvalidValueExamples
     */
    public function test_it_throws_with_invalid_payload(array $values, Exception $expectedException): void
    {
        $this->expectExceptionObject($expectedException);

        Transaction::fromArray($values);
    }

    /**
     * @return iterable<array-key, array{0: array, 1: Exception}>
     */
    public function getInvalidValueExamples(): iterable
    {
        $perfectValues = [
            'date' => 'some',
            'name' => '',
            'description' => '',
            'amount' => 1.0,
        ];

        yield from ValueObjectPayloadAssertion::notEmptyString($perfectValues, 'date');
        yield from ValueObjectPayloadAssertion::string($perfectValues, 'name');
        yield from ValueObjectPayloadAssertion::string($perfectValues, 'description');
        yield from ValueObjectPayloadAssertion::float($perfectValues, 'amount');
    }
}
