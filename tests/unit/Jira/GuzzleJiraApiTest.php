<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Jira;

use GuzzleHttp\ClientInterface;
use Oqq\Office\Exception\InvalidArgumentException;
use Oqq\Office\Jira\GuzzleJiraApi;
use Oqq\Office\Jira\Api\IssueKeys;
use Oqq\Office\Jira\JiraUser;
use Oqq\Office\Jira\Tempo\Comment;
use Oqq\Office\Jira\Tempo\IssueKey;
use Oqq\Office\Jira\Tempo\TimeSpentSeconds;
use Oqq\Office\Jira\Tempo\WorklogId;
use Oqq\Office\Test\Jira\Api\PayloadExample as ApiPayloadExample;
use Oqq\Office\Test\Jira\Tempo\PayloadExample as TempoPayloadExample;
use Oqq\Office\Util\DateTime;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Argument\Token\TokenInterface;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @covers \Oqq\Office\Jira\GuzzleJiraApi
 */
final class GuzzleJiraApiTest extends TestCase
{
    use ProphecyTrait;

    /** @var ObjectProphecy<ClientInterface> */
    private ObjectProphecy $client;
    private GuzzleJiraApi $jiraApi;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = $this->prophesize(ClientInterface::class);
        $this->jiraApi = new GuzzleJiraApi($this->client->reveal());
    }

    public function testItResolvesIssues(): void
    {
        $responsePayload = [
            'issues' => [
                ApiPayloadExample::issue(),
                ApiPayloadExample::issue(),
            ],
        ];

        $body = $this->prophesize(StreamInterface::class);
        $body->__toString()->willReturn(\json_encode($responsePayload, \JSON_THROW_ON_ERROR));

        $response = $this->prophesize(ResponseInterface::class);
        $response->getBody()->willReturn($body);

        /** @noinspection PhpParamsInspection */
        $this->client->request(
            Argument::any(),
            Argument::any(),
            Argument::allOf(
                $this->createRecursiveSearchArgument('TEST-1'),
                $this->createRecursiveSearchArgument('TEST-10'),
            )
        )->willReturn($response);

        $issues = $this->jiraApi->getIssues(
            IssueKeys::fromArray([
                'TEST-1',
                'TEST-10',
            ])
        );

        Assert::assertCount(2, $issues);
    }

    /**
     * @dataProvider invalidIssuesResponseProvider
     */
    public function testItThrowsWithInvalidResponseForIssues(\Exception $expectedException, array $responsePayload): void
    {
        $this->expectExceptionObject($expectedException);

        $response = $this->prophesize(ResponseInterface::class);
        $response->getBody()->willReturn(\json_encode($responsePayload, \JSON_THROW_ON_ERROR));

        /** @noinspection PhpParamsInspection */
        $this->client->request(Argument::cetera())->willReturn($response);

        $this->jiraApi->getIssues(IssueKeys::fromArray([]));
    }

    /**
     * @return iterable<string, array{0: \Exception, 1: array}>
     */
    public function invalidIssuesResponseProvider(): iterable
    {
        yield 'missing issues key' => [
            new InvalidArgumentException('Expected the key "issues" to exist'),
            [],
        ];

        yield 'invalid activities content' => [
            new InvalidArgumentException('Expected an array. Got: boolean'),
            [
                'issues' => false,
            ],
        ];
    }

    public function testItResolvesWorklogs(): void
    {
        $responsePayload = [
            TempoPayloadExample::worklog(),
            TempoPayloadExample::worklog(),
        ];

        $body = $this->prophesize(StreamInterface::class);
        $body->__toString()->willReturn(\json_encode($responsePayload, \JSON_THROW_ON_ERROR));

        $response = $this->prophesize(ResponseInterface::class);
        $response->getBody()->willReturn($body);

        /** @noinspection PhpParamsInspection */
        $this->client->request(
            Argument::any(),
            Argument::any(),
            Argument::allOf(
                $this->createRecursiveSearchArgument('some'),
                $this->createRecursiveSearchArgument('2021-01-01'),
                $this->createRecursiveSearchArgument('2021-01-31'),
            )
        )->willReturn($response);

        $worklogs = $this->jiraApi->getWorklogs(
            JiraUser::fromString('some'),
            DateTime::fromString('2021-01-01', 'Y-m-d'),
            DateTime::fromString('2021-01-31', 'Y-m-d'),
        );

        Assert::assertCount(2, $worklogs);
    }

    public function testItDeletesWorklog(): void
    {
        $this->client->request(
            Argument::any(),
            Argument::containingString('/5275613')
        )->shouldBeCalledOnce();

        $this->jiraApi->deleteWorkLog(WorklogId::fromInt(5275613));
    }

    public function testItCreatesWorklog(): void
    {
        /** @noinspection PhpParamsInspection */
        $this->client->request(
            Argument::any(),
            Argument::any(),
            Argument::allOf(
                $this->createRecursiveSearchArgument('some'),
                $this->createRecursiveSearchArgument('test'),
                $this->createRecursiveSearchArgument('TEST-1'),
                $this->createRecursiveSearchArgument('2021-01-01'),
                $this->createRecursiveSearchArgument(3600),
            )
        )->shouldBeCalledOnce();

        $this->jiraApi->createWorklog(
            JiraUser::fromString('some'),
            IssueKey::fromString('TEST-1'),
            DateTime::fromString('2021-01-01', 'Y-m-d'),
            TimeSpentSeconds::fromInteger(3600),
            Comment::fromString('test'),
        );
    }

    private function createRecursiveSearchArgument(int | string $searchedValue): TokenInterface
    {
        return Argument::allOf(
            Argument::type('iterable'),
            Argument::that(
                /** @param iterable<int|string|iterable<int|string>> $value */
                static fn (iterable $value): bool => self::searchRecursiveValue($value, $searchedValue)
            ),
        );
    }

    /**
     * @param iterable<int|string|iterable<int|string>> $value
     */
    private static function searchRecursiveValue(iterable $value, int | string $searchedValue): bool
    {
        foreach ($value as $item) {
            if ($item === $searchedValue) {
                return true;
            }

            if (\is_string($item) && \is_string($searchedValue) && \str_contains($item, $searchedValue)) {
                return true;
            }

            if (\is_iterable($item) && self::searchRecursiveValue($item, $searchedValue)) {
                return true;
            }
        }

        return false;
    }
}
