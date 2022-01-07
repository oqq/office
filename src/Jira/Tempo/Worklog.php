<?php

declare(strict_types=1);

namespace Oqq\Office\Jira\Tempo;

use DateTimeImmutable;
use Oqq\Office\Util\Assertion;
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
        Assertion::keyExists($values, 'tempoWorklogId');
        Assertion::integer($values['tempoWorklogId']);

        Assertion::keyExists($values, 'issue');
        Assertion::isArray($values['issue']);

        Assertion::keyExists($values, 'started');
        Assertion::string($values['started']);

        Assertion::keyExists($values, 'timeSpentSeconds');
        Assertion::integer($values['timeSpentSeconds']);

        Assertion::keyExists($values, 'comment');
        Assertion::string($values['comment']);

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
