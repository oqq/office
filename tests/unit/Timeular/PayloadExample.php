<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Timeular;

final class PayloadExample
{
    public static function activity(): array
    {
        return [
            'id' => '1',
            'name' => 'test',
            'color' => '#fff',
            'spaceId' => '2',
        ];
    }

    public static function tag(): array
    {
        return [
            'id' => 1,
            'label' => 'test',
        ];
    }

    public static function tags(): array
    {
        return [
            [
                'id' => 1,
                'label' => 'tag_1',
            ],
            [
                'id' => 2,
                'label' => 'tag_2',
            ],
        ];
    }

    public static function note(): array
    {
        return [
            'text' => 'test',
            'tags' => self::tags(),
        ];
    }

    public static function duration(): array
    {
        return [
            'startedAt' =>  '2021-01-01T10:00:00.000',
            'stoppedAt' => '2021-01-01T10:01:00.000',
        ];
    }

    public static function timeEntry(): array
    {
        return [
            'id' =>  '1',
            'activityId' => '2',
            'note' => self::note(),
            'duration' => self::duration(),
        ];
    }
}
