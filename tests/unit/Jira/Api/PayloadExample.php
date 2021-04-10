<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Jira\Api;

final class PayloadExample
{
    public static function issueKey(): string
    {
        return 'TEST-10';
    }

    public static function issue(): array
    {
        return [
            'id' => '1',
            'key' => self::issueKey(),
        ];
    }
}
