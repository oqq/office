<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Service\Banking;

use Exception;
use Oqq\Office\Service\Banking\Transactions;
use Oqq\Office\Test\ValueObjectPayloadAssertion;
use Oqq\Office\Test\ValueObjectPayloadExample;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Service\Banking\Transactions
 */
final class TransactionsTest extends TestCase
{
    /**
     * @dataProvider getValidValueExamples
     */
    public function test_it_creates_from_values(array $values): void
    {
        $valueObject = Transactions::fromArray($values);

        Assert::assertSame($values, $valueObject->toArray());
    }

    /**
     * @return iterable<array-key, array{0: array}>
     */
    public function getValidValueExamples(): iterable
    {
        yield [[]];

        yield [
            [
                ValueObjectPayloadExample::transaction(),
            ],
        ];

        yield [
            [
                ValueObjectPayloadExample::transaction(),
                ValueObjectPayloadExample::transaction(),
            ],
        ];
    }

    /**
     * @dataProvider getInvalidValueExamples
     */
    public function test_it_throws_with_invalid_payload(array $values, Exception $expectedException): void
    {
        $this->expectExceptionObject($expectedException);

        Transactions::fromArray($values);
    }

    /**
     * @return iterable<array-key, array{0: array, 1: Exception}>
     */
    public function getInvalidValueExamples(): iterable
    {
        $perfectValues = [
            ValueObjectPayloadExample::transaction(),
            ValueObjectPayloadExample::transaction(),
        ];

        yield from ValueObjectPayloadAssertion::arrayCollection($perfectValues);
    }
}
