<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Jira;

use Oqq\Office\Jira\Worklog;
use Oqq\Office\Test\ValueObjectPayloadAssertion;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Jira\Worklog
 */
final class WorklogTest extends TestCase
{
    public function testItWillCreateFromPerfectPayload(): void
    {
        $valueObject = Worklog::fromArray([
            'tempoWorklogId' => 1,
            'issue' => PayloadExample::issue(),
            'started' => '2021-01-10',
            'timeSpentSeconds' => 60,
        ]);

        Assert::assertSame('1', $valueObject->worklogId()->toString());
        Assert::assertSame('2021-01-10', $valueObject->started()->format('Y-m-d'));

        $valueObject->issue();
    }

    /**
     * @dataProvider invalidPayloadProvider
     */
    public function testItThrowsWithInvalidPayload(\Exception $expectedException, array $payloadExample): void
    {
        $this->expectExceptionObject($expectedException);

        Worklog::fromArray($payloadExample);
    }

    /**
     * @return iterable<string, array{0: \Exception, 1: array}>
     */
    public function invalidPayloadProvider(): iterable
    {
        $perfectValues = [
            'tempoWorklogId' => 1,
            'issue' => PayloadExample::issue(),
            'started' => '2021-01-10',
            'timeSpentSeconds' => 60,
        ];

        yield from ValueObjectPayloadAssertion::integer($perfectValues, 'tempoWorklogId');
        yield from ValueObjectPayloadAssertion::array($perfectValues, 'issue');
        yield from ValueObjectPayloadAssertion::string($perfectValues, 'started');
        yield from ValueObjectPayloadAssertion::positiveInteger($perfectValues, 'timeSpentSeconds');
    }
}
