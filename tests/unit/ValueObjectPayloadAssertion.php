<?php

declare(strict_types=1);

namespace Oqq\Office\Test;

use Oqq\Office\Exception\InvalidArgumentException;

final class ValueObjectPayloadAssertion
{
    private function __construct()
    {
    }

    /**
     * @return iterable<string, array{0: \Exception, 1: array}>
     */
    public static function string(array $perfectValues, string $key): iterable
    {
        yield 'missing value for ' . $key => [
            new InvalidArgumentException('Expected the key "' . $key . '" to exist'),
            self::removeKey($perfectValues, $key),
        ];

        yield 'invalid type for ' . $key => [
            new InvalidArgumentException('Expected a string. Got: integer'),
            self::replaceKey($perfectValues, $key, 5),
        ];
    }

    /**
     * @return iterable<string, array{0: \Exception, 1: array}>
     */
    public static function nonEmptyString(array $perfectValues, string $key): iterable
    {
        yield from self::string($perfectValues, $key);

        yield 'empty value for ' . $key => [
            new InvalidArgumentException('Expected a different value than ""'),
            self::replaceKey($perfectValues, $key, ''),
        ];
    }

    /**
     * @return iterable<string, array{0: \Exception, 1: array}>
     */
    public static function integer(array $perfectValues, string $key): iterable
    {
        yield 'missing value for ' . $key => [
            new InvalidArgumentException('Expected the key "' . $key . '" to exist'),
            self::removeKey($perfectValues, $key),
        ];

        yield 'invalid type for ' . $key => [
            new InvalidArgumentException('Expected an integer. Got: string'),
            self::replaceKey($perfectValues, $key, '5'),
        ];
    }

    /**
     * @return iterable<string, array{0: \Exception, 1: array}>
     */
    public static function positiveInteger(array $perfectValues, string $key): iterable
    {
        yield 'missing value for ' . $key => [
            new InvalidArgumentException('Expected the key "' . $key . '" to exist'),
            self::removeKey($perfectValues, $key),
        ];

        yield 'invalid type for ' . $key => [
            new InvalidArgumentException('Expected a positive integer. Got: "5"'),
            self::replaceKey($perfectValues, $key, '5'),
        ];

        yield 'invalid zero value for ' . $key => [
            new InvalidArgumentException('Expected a positive integer. Got: 0'),
            self::replaceKey($perfectValues, $key, 0),
        ];

        yield 'invalid negative value for ' . $key => [
            new InvalidArgumentException('Expected a positive integer. Got: -5'),
            self::replaceKey($perfectValues, $key, -5),
        ];
    }

    /**
     * @return iterable<string, array{0: \Exception, 1: array}>
     */
    public static function array(array $perfectValues, string $key): iterable
    {
        yield 'missing value for ' . $key => [
            new InvalidArgumentException('Expected the key "' . $key . '" to exist'),
            self::removeKey($perfectValues, $key),
        ];

        yield 'invalid type for ' . $key . ' value' => [
            new InvalidArgumentException('Expected an array. Got: integer'),
            self::replaceKey($perfectValues, $key, 5),
        ];
    }

    /**
     * @return iterable<string, array{0: \Exception, 1: array}>
     */
    public static function stringList(array $perfectValues, string $key): iterable
    {
        yield 'missing value for ' . $key => [
            new InvalidArgumentException('Expected the key "' . $key . '" to exist'),
            self::removeKey($perfectValues, $key),
        ];

        yield 'invalid type for ' . $key => [
            new InvalidArgumentException('Expected list - non-associative array'),
            self::replaceKey($perfectValues, $key, ['alpha' => 1]),
        ];

        yield 'invalid type for ' . $key . ' value' => [
            new InvalidArgumentException('Expected a string. Got: integer'),
            self::replaceKey($perfectValues, $key, [5]),
        ];
    }

    /**
     * @return iterable<string, array{0: \Exception, 1: array}>
     */
    public static function arrayList(array $perfectValues, string $key): iterable
    {
        yield 'missing value for ' . $key => [
            new InvalidArgumentException('Expected the key "' . $key . '" to exist'),
            self::removeKey($perfectValues, $key),
        ];

        yield 'invalid type for ' . $key => [
            new InvalidArgumentException('Expected list - non-associative array'),
            self::replaceKey($perfectValues, $key, ['alpha' => 1]),
        ];

        yield 'invalid type for ' . $key . ' value' => [
            new InvalidArgumentException('Expected an array. Got: integer'),
            self::replaceKey($perfectValues, $key, [5]),
        ];
    }

    /**
     * @return iterable<string, array{0: \Exception, 1: array}>
     */
    public static function map(array $perfectValues, string $key): iterable
    {
        yield 'missing value for ' . $key => [
            new InvalidArgumentException('Expected the key "' . $key . '" to exist'),
            self::removeKey($perfectValues, $key),
        ];

        yield 'invalid type for ' . $key => [
            new InvalidArgumentException('Expected map - associative array'),
            self::replaceKey($perfectValues, $key, [5]),
        ];
    }

    private static function removeKey(array $values, string $key): array
    {
        return \array_diff_key($values, [$key => true]);
    }

    private static function replaceKey(array $values, string $key, mixed $newValue): array
    {
        return \array_merge($values, [$key => $newValue]);
    }
}
