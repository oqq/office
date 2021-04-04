<?php

declare(strict_types=1);

namespace Oqq\Office\Timeular;

use DateTimeInterface;

interface TimeularApi
{
    public const DATETIME_FORMAT = 'Y-m-d\TH:i:s.v';

    public function getActivities(): Activities;

    public function getTimeEntries(DateTimeInterface $start, DateTimeInterface $end): TimeEntries;
}
