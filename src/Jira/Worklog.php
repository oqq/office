<?php

declare(strict_types=1);

namespace Oqq\Office\Jira;

use DateTimeImmutable;
use Oqq\Office\Util\Assert;
use Oqq\Office\Util\DateTime;

final class Worklog
{
    private WorklogId $worklogId;
    private Issue $issue;
    private DateTimeImmutable $started;
    private int $timeSpentSeconds;

    public static function fromArray(array $values): self
    {
        Assert::keyExists($values, 'tempoWorklogId');
        Assert::integer($values['tempoWorklogId']);

        Assert::keyExists($values, 'issue');
        Assert::isArray($values['issue']);

        Assert::keyExists($values, 'started');
        Assert::string($values['started']);

        Assert::keyExists($values, 'timeSpentSeconds');
        Assert::positiveInteger($values['timeSpentSeconds']);

        $worklogId = WorklogId::fromInt($values['tempoWorklogId']);
        $issue = Issue::fromArray($values['issue']);
        $started = DateTime::fromString($values['started'], 'Y-m-d|+');
        
        return new self($worklogId, $issue, $started, $values['timeSpentSeconds']);
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

    private function __construct(WorklogId $worklogId, Issue $issue, DateTimeImmutable $started, int $timeSpentSeconds)
    {
        $this->worklogId = $worklogId;
        $this->issue = $issue;
        $this->started = $started;
        $this->timeSpentSeconds = $timeSpentSeconds;
    }
}
