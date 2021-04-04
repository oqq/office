<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Jira;

final class PayloadExample
{
    public static function issueKey(): string
    {
        return 'TEST-10';
    }

    public static function issue(): array
    {
        return [
            'key' => self::issueKey(),
        ];
    }

    public static function worklog(): array
    {
        return [
            'tempoWorklogId' => 1,
            'issue' => self::issue(),
            'started' => '2021-01-10',
            'timeSpentSeconds' => 60,
        ];
    }
}
