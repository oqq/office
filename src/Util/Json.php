<?php

declare(strict_types=1);

namespace Oqq\Office\Util;

final class Json
{
    private const DEFAULT_FLAGS_BITMASK = 0;

    public static function encode(mixed $value, int $flags = self::DEFAULT_FLAGS_BITMASK): string
    {
        return \json_encode($value, flags: \JSON_THROW_ON_ERROR | $flags);
    }

    public static function decode(string $json, int $flags = self::DEFAULT_FLAGS_BITMASK): array
    {
        $result = \json_decode($json, associative: true, flags: \JSON_THROW_ON_ERROR | $flags);
        assert(\is_array($result));

        return $result;
    }
}
