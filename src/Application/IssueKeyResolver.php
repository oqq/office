<?php

declare(strict_types=1);

namespace Oqq\Office\Application;

use Oqq\Office\Jira\Tempo\IssueKey;
use Oqq\Office\Timeular\TimeEntry;

interface IssueKeyResolver
{
    public function findIssueKeyFromTimeEntry(TimeEntry $timeEntry): ?IssueKey;
}
