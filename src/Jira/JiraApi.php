<?php

declare(strict_types=1);

namespace Oqq\Office\Jira;

use DateTimeInterface;

interface JiraApi
{
    public function getIssues(IssueKeys $issueKeys): Issues;

    public function getWorklogs(JiraUser $jiraUser, DateTimeInterface $from, DateTimeInterface $to): Worklogs;

    public function deleteWorkLog(WorklogId $worklogId): void;

    public function createWorklog(
        JiraUser $jiraUser,
        string $comment,
        IssueKey $issueKey,
        DateTimeInterface $started,
        TimeSpent $timeSpent
    ): void;
}
