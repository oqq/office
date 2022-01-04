<?php

declare(strict_types=1);

namespace Oqq\Office\Application;

use DateTimeImmutable;
use Oqq\Office\Jira\Tempo\Comment;
use Oqq\Office\Jira\Tempo\IssueKey;
use Oqq\Office\Jira\Tempo\TimeSpentSeconds;
use Oqq\Office\Timeular\Activity;

final class MaybeWorklog
{
    public function __construct(
        private Activity $activity,
        private DateTimeImmutable $date,
        private TimeSpentSeconds $timeSpentSeconds,
        private Comment $comment,
        private ?IssueKey $issueKey,
    ) {
    }

    public function activity(): Activity
    {
        return $this->activity;
    }

    public function date(): DateTimeImmutable
    {
        return $this->date;
    }

    public function timeSpentSeconds(): TimeSpentSeconds
    {
        return $this->timeSpentSeconds;
    }

    public function comment(): Comment
    {
        return $this->comment;
    }

    public function hasIssueKey(): bool
    {
        return null !== $this->issueKey;
    }

    public function optionalIssueKey(): ?IssueKey
    {
        return $this->issueKey;
    }
}
