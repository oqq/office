<?php

declare(strict_types=1);

namespace Oqq\Office\Application;

use Oqq\Office\Jira\Api\IssueKey;
use Oqq\Office\Timeular\TimeEntry;

interface IssueKeyResolver
{
    public function findIssueKeyFromTimeEntry(TimeEntry $timeEntry): ?IssueKey;
}
