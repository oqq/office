<?php

declare(strict_types=1);

namespace Oqq\Office\Application;

use Generator;
use IteratorAggregate;
use Oqq\Office\Jira\Tempo\Comment;
use Oqq\Office\Jira\Tempo\IssueKey;
use Oqq\Office\Jira\Tempo\TimeSpentSeconds;
use Oqq\Office\Timeular\Activities;
use Oqq\Office\Timeular\Activity;
use Oqq\Office\Timeular\TimeEntries;
use Oqq\Office\Timeular\TimeEntry;

/**
 * @implements IteratorAggregate<MaybeWorklog>
 */
final class MaybeWorklogs implements IteratorAggregate
{
    public function __construct(
        private IssueKeyResolver $issueKeyResolver,
        private Activities $activities,
        private TimeEntries $timeEntries
    ) {
    }

    /**
     * @return Generator<MaybeWorklog>
     */
    public function getIterator(): Generator
    {
        foreach ($this->timeEntries as $timeEntry) {
            $date = $timeEntry->duration()->date();
            $issueKey = $this->getIssueKeyFromTimeEntry($timeEntry);
            $activity = $this->activities->grabWithId($timeEntry->activityId());
            $timeSpent = TimeSpentSeconds::fromInteger($timeEntry->duration()->timeSpentSeconds());
            $comment = $this->getComment($activity, $timeEntry);

            yield new MaybeWorklog($activity, $date, $timeSpent, $comment, $issueKey);
        }
    }

    public function filterBySpace(string $spaceId): self
    {
        $clone = clone $this;
        $clone->timeEntries = $this->timeEntries->filter(
            fn (TimeEntry $timeEntry): bool => (
                $spaceId === $this->activities->grabWithId($timeEntry->activityId())->spaceId()
            )
        );

        return $clone;
    }

    public function timeSpent(): TimeSpentSeconds
    {
        return TimeSpentSeconds::fromInteger($this->timeEntries->timeSpentSeconds());
    }

    private function getIssueKeyFromTimeEntry(TimeEntry $timeEntry): ?IssueKey
    {
        return $this->issueKeyResolver->findIssueKeyFromTimeEntry($timeEntry);
    }

    private function getComment(Activity $activity, TimeEntry $timeEntry): Comment
    {
        $activityName = $activity->name();
        $note = $timeEntry->note()->getFilteredText();
        $extendedNote = $note ? $note . ' (' . $activityName . ')' : $activityName;

        $content = match ($activity->name()) {
            'Code' => $note,
            default => $extendedNote,
        };

        return Comment::fromString($content);
    }
}
