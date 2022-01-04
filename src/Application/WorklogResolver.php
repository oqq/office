<?php

declare(strict_types=1);

namespace Oqq\Office\Application;

use Oqq\Office\Jira\JiraApi;
use Oqq\Office\Jira\JiraUser;
use Oqq\Office\Jira\Tempo\IssueKey;
use Oqq\Office\Timeular\TimeEntries;
use Oqq\Office\Timeular\TimeularApi;
use Oqq\Office\Util\DateTime;

final class WorklogResolver
{
    public function __construct(
        private TimeularApi $timeularApi,
        private IssueKeyResolver $issueKeyResolver,
        private JiraApi $jiraApi,
        private JiraUser $jiraUser,
        private string $jiraBaseUrl,
    ) {
    }

    public function createUrlForIssue(IssueKey $issueKey): string
    {
        return \sprintf('%s/browse/%s', $this->jiraBaseUrl, $issueKey->toString());
    }

    /**
     * @return iterable<Week, MaybeWorklogs>
     */
    public function getWorklogsForMonthByWeek(Month $month): iterable
    {
        $activities = $this->timeularApi->getActivities();
        $timeEntriesForMonth = $this->getTimeEntriesForMonth($month);

        foreach ($timeEntriesForMonth->perWeek() as $week => $timeEntriesPerWeek) {
            yield Week::fromString($week) => new MaybeWorklogs($this->issueKeyResolver, $activities, $timeEntriesPerWeek);
        }
    }

    public function createWorklog(MaybeWorklog $worklog): void
    {
        $this->jiraApi->createWorklog(
            $this->jiraUser,
            $worklog->optionalIssueKey(),
            $worklog->date(),
            $worklog->timeSpentSeconds(),
            $worklog->comment()
        );
    }

    private function getTimeEntriesForMonth(Month $month): TimeEntries
    {
        $start = DateTime::fromString($month->value() . '-01', 'Y-m-d|');
        $end = DateTime::fromString($start->format('Y-m-t') . 'T23:59:59.999999', 'Y-m-d\TH:i:s.u');

        return $this->timeularApi->getTimeEntries($start, $end);
    }
}
