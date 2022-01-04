<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Application;

use Oqq\Office\Application\IssueKeyResolver;
use Oqq\Office\Application\ListTimeEntriesCommand;
use Oqq\Office\Application\WorklogResolver;
use Oqq\Office\Jira\JiraApi;
use Oqq\Office\Jira\JiraUser;
use Oqq\Office\Timeular\Activities;
use Oqq\Office\Timeular\TimeEntries;
use Oqq\Office\Timeular\TimeularApi;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @covers \Oqq\Office\Application\ListTimeEntriesCommand
 */
final class ListTimeEntriesCommandTest extends TestCase
{
    use ProphecyTrait;

    /** @var ObjectProphecy<TimeularApi> */
    private ObjectProphecy $timeularApi;

    /** @var ObjectProphecy<IssueKeyResolver> */
    private ObjectProphecy $issueKeyResolver;

    /** @var ObjectProphecy<JiraApi> */
    private ObjectProphecy $jiraApi;

    private CommandTester $commandTester;

    protected function setUp(): void
    {
        parent::setUp();

        $this->timeularApi = $this->prophesize(TimeularApi::class);
        $this->issueKeyResolver = $this->prophesize(IssueKeyResolver::class);
        $this->jiraApi = $this->prophesize(JiraApi::class);

        $worklogResolver = new WorklogResolver(
            $this->timeularApi->reveal(),
            $this->issueKeyResolver->reveal(),
            $this->jiraApi->reveal(),
            JiraUser::fromString('test'),
            ''
        );

        $command = new ListTimeEntriesCommand($worklogResolver);

        $this->commandTester = new CommandTester($command);
    }

    public function testItDisplaysWorklogs(): void
    {
        $this->timeularApi->getActivities()->willReturn(Activities::fromArray([]));
        $this->timeularApi->getTimeEntries(Argument::cetera())->willReturn(TimeEntries::fromArray([]));

        $exitCode = $this->commandTester->execute([]);

        Assert::assertSame(0, $exitCode);
    }
}
