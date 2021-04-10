<?php

declare(strict_types=1);

namespace Oqq\Office\Application;

use Oqq\Office\Exception\RuntimeException;
use Oqq\Office\Jira\Api\IssueKey;
use Oqq\Office\Timeular\Tags;
use Oqq\Office\Timeular\TimeEntry;

final class LabelIssueKeyResolver implements IssueKeyResolver
{
    public function findIssueKeyFromTimeEntry(TimeEntry $timeEntry): ?IssueKey
    {
        $foundIssueKey = null;
        $labels = $this->getLabelsFromTags($timeEntry->note()->tags());

        foreach ($labels as $label) {
            if (! $this->labelMatchesExpectedPattern($label)) {
                continue;
            }

            if (null !== $foundIssueKey) {
                throw new RuntimeException('Have found more than one issue key in time entry');
            }

            $foundIssueKey = IssueKey::fromString($label);
        }

        return $foundIssueKey;
    }

    /**
     * @return iterable<array-key, string>
     */
    private function getLabelsFromTags(Tags $tags): iterable
    {
        foreach ($tags as $tag) {
            yield $tag->label();
        }
    }

    private function labelMatchesExpectedPattern(string $label): bool
    {
        return 1 === preg_match(IssueKey::PATTERN, $label);
    }
}
