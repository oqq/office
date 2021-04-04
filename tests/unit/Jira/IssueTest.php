<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Jira;

use Oqq\Office\Jira\Issue;
use Oqq\Office\Test\ValueObjectPayloadAssertion;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Jira\Issue
 */
final class IssueTest extends TestCase
{
    public function testItWillCreateFromPerfectPayload(): void
    {
        $valueObject = Issue::fromArray([
            'key' =>  PayloadExample::issueKey(),
        ]);

        Assert::assertSame(PayloadExample::issueKey(), $valueObject->issueKey()->toString());
    }
    /**
     * @dataProvider invalidPayloadProvider
     */
    public function testItThrowsWithInvalidPayload(\Exception $expectedException, array $payloadExample): void
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
            'key' =>  PayloadExample::issueKey(),
        ];

        yield from ValueObjectPayloadAssertion::string($perfectValues, 'key');
    }
}
