<?php

declare(strict_types=1);

namespace Oqq\Office\Jira\Tempo;

use DateTimeImmutable;
use Oqq\Office\Util\Assert;
use Oqq\Office\Util\DateTime;

final class Worklog
{
    private WorklogId $worklogId;
    private Issue $issue;
    private DateTimeImmutable $started;
    private TimeSpentSeconds $timeSpentSeconds;
    private Comment $comment;

    public static function fromArray(array $values): self
    {
        Assert::keyExists($values, 'tempoWorklogId');
        Assert::integer($values['tempoWorklogId']);

        Assert::keyExists($values, 'issue');
        Assert::isArray($values['issue']);

        Assert::keyExists($values, 'started');
        Assert::string($values['started']);

        Assert::keyExists($values, 'timeSpentSeconds');
        Assert::integer($values['timeSpentSeconds']);

        Assert::keyExists($values, 'comment');
        Assert::string($values['comment']);

        $worklogId = WorklogId::fromInt($values['tempoWorklogId']);
        $issue = Issue::fromArray($values['issue']);
        $started = DateTime::fromString($values['started'], 'Y-m-d|+');
        $timeSpentSeconds = TimeSpentSeconds::fromInteger($values['timeSpentSeconds']);
        $comment = Comment::fromString($values['comment']);

        return new self($worklogId, $issue, $started, $timeSpentSeconds, $comment);
    }

    public function worklogId(): WorklogId
    {
        return $this->worklogId;
    }

    public function issue(): Issue
    {
        return $this->issue;
    }

    public function started(): DateTimeImmutable
    {
        return $this->started;
    }

    public function timeSpentSeconds(): TimeSpentSeconds
    {
        return $this->timeSpentSeconds;
    }

    public function comment(): Comment
    {
        return $this->comment;
    }

    private function __construct(
        WorklogId $worklogId,
        Issue $issue,
        DateTimeImmutable $started,
        TimeSpentSeconds $timeSpentSeconds,
        Comment $comment
    ) {
        $this->worklogId = $worklogId;
        $this->issue = $issue;
        $this->started = $started;
        $this->timeSpentSeconds = $timeSpentSeconds;
        $this->comment = $comment;
    }
}
