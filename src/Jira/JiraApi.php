<?php

declare(strict_types=1);

namespace Oqq\Office\Jira;

use DateTimeInterface;

interface JiraApi
{
    public function getIssues(IssueKeys $issueKeys): Issues;

    public function getWorklogs(string $worker, DateTimeInterface $from, DateTimeInterface $to): Worklogs;

    public function deleteWorkLog(WorklogId $worklogId): void;

    public function createWorklog(string $worker, string $comment, IssueKey $issueKey, DateTimeInterface $started, int $timeSpentSeconds): void;
}
