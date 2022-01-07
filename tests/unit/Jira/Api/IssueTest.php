<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Jira\Api;

use Oqq\Office\Jira\Api\Issue;
use Oqq\Office\Test\ValueObjectPayloadAssertion;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Jira\Api\Issue
 */
final class IssueTest extends TestCase
{
    public function testItWillCreateFromPerfectPayload(): void
    {
        $valueObject = Issue::fromArray([
            'id' => '1',
            'key' =>  PayloadExample::issueKey(),
        ]);

        Assert::assertSame(PayloadExample::issueKey(), $valueObject->issueKey()->toString());
    }
    /**
     * @dataProvider invalidPayloadProvider
     */
    public function testItThrowsWithInvalidPayload(array $payloadExample, \Exception $expectedException): void
    {
        $this->expectExceptionObject($expectedException);

        Issue::fromArray($payloadExample);
    }

    /**
     * @return iterable<string, array{0: \Exception, 1: array}>
     */
    public function invalidPayloadProvider(): iterable
    {
        $perfectValues = [
            'id' => '1',
            'key' =>  PayloadExample::issueKey(),
        ];

        yield from ValueObjectPayloadAssertion::string($perfectValues, 'id');
        yield from ValueObjectPayloadAssertion::string($perfectValues, 'key');
    }
}
