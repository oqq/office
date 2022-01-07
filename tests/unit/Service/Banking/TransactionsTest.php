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
     * @dataProvider getValidPayloadExamples
     */
    public function test_it_creates_from_payload(array $payload): void
    {
        $valueObject = Transactions::fromPayload($payload);

        Assert::assertSame($payload, $valueObject->toArray());
    }

    /**
     * @return iterable<array-key, array{0: array}>
     */
    public function getValidPayloadExamples(): iterable
    {
        yield [
            [],
        ];

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
     * @dataProvider getInvalidPayloadExamples
     */
    public function test_it_throws_with_invalid_payload(array $payload, Exception $expectedException): void
    {
        $this->expectExceptionObject($expectedException);

        Transactions::fromPayload($payload);
    }

    /**
     * @return iterable<array-key, array{0: array, 1: Exception}>
     */
    public function getInvalidPayloadExamples(): iterable
    {
        $perfectValues = [
            ValueObjectPayloadExample::transaction(),
            ValueObjectPayloadExample::transaction(),
        ];

        yield from ValueObjectPayloadAssertion::arrayCollection($perfectValues);
    }
}
