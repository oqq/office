<?php

declare(strict_types=1);

namespace Oqq\Office\Test;

final class ValueObjectPayloadExample
{
    public static function transaction(): array
    {
        return [
            'date' => 'some value',
            'name' => 'some value',
            'description' => 'some value',
            'amount' => 2.00,
        ];
    }
}
