<?php

declare(strict_types=1);

namespace Oqq\Office\Jira;

use DateTimeInterface;
use Oqq\Office\Jira\Tempo\IssueKey;
use Oqq\Office\Jira\Api\IssueKeys;
use Oqq\Office\Jira\Api\Issues;
use Oqq\Office\Jira\Tempo\Comment;
use Oqq\Office\Jira\Tempo\TimeSpentSeconds;
use Oqq\Office\Jira\Tempo\WorklogId;
use Oqq\Office\Jira\Tempo\Worklogs;

interface JiraApi
{
    public function getIssues(IssueKeys $issueKeys): Issues;

    public function getWorklogs(JiraUser $jiraUser, DateTimeInterface $from, DateTimeInterface $to): Worklogs;

    public function deleteWorkLog(WorklogId $worklogId): void;

    public function createWorklog(JiraUser $jiraUser, IssueKey $issueKey, DateTimeInterface $started, TimeSpentSeconds $timeSpent, Comment $comment): void;
}
