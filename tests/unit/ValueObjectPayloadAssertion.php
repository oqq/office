<?php

declare(strict_types=1);

namespace Oqq\Office\Test;

use Exception;
use Oqq\Office\Exception\AssertionFailedException;

final class ValueObjectPayloadAssertion
{
    /**
     * @return iterable<string, array{0: array, 1: Exception}>
     */
    public static function exist(array $perfectValues, string $key): iterable
    {
        yield 'missing value for ' . $key => [
            self::removeKey($perfectValues, $key),
            self::createException('Expected the key "' . $key . '" to exist'),
        ];
    }

    /**
     * @return iterable<string, array{0: array, 1: Exception}>
     */
    public static function string(array $perfectValues, string $key): iterable
    {
        yield from self::exist($perfectValues, $key);

        yield 'invalid type for ' . $key => [
            self::replaceKey($perfectValues, $key, 5),
            self::createException('Expected a string. Got: integer'),
        ];
    }

    /**
     * @return iterable<string, array{0: array, 1: Exception}>
     */
    public static function notEmptyString(array $perfectValues, string $key): iterable
    {
        yield from self::string($perfectValues, $key);

        yield 'empty value for ' . $key => [
            self::replaceKey($perfectValues, $key, ''),
            self::createException('Expected a different value than ""'),
        ];
    }

    /**
     * @return iterable<string, array{0: array, 1: Exception}>
     */
    public static function integer(array $perfectValues, string $key): iterable
    {
        yield from self::exist($perfectValues, $key);

        yield 'invalid type for ' . $key => [
            self::replaceKey($perfectValues, $key, '5'),
            self::createException('Expected an integer. Got: string'),
        ];
    }

    /**
     * @return iterable<string, array{0: array, 1: Exception}>
     */
    public static function positiveInteger(array $perfectValues, string $key): iterable
    {
        yield from self::exist($perfectValues, $key);

        yield 'invalid type for ' . $key => [
            self::replaceKey($perfectValues, $key, '5'),
            self::createException('Expected a positive integer. Got: "5"'),
        ];

        yield 'invalid zero value for ' . $key => [
            self::replaceKey($perfectValues, $key, 0),
            self::createException('Expected a positive integer. Got: 0'),
        ];

        yield 'invalid negative value for ' . $key => [
            self::replaceKey($perfectValues, $key, -5),
            self::createException('Expected a positive integer. Got: -5'),
        ];
    }

    /**
     * @return iterable<string, array{0: array, 1: Exception}>
     */
    public static function float(array $perfectValues, string $key): iterable
    {
        yield from self::exist($perfectValues, $key);

        yield 'invalid value type for ' . $key => [
            self::replaceKey($perfectValues, $key, '5.00'),
            self::createException('Expected a float. Got: string'),
        ];

        yield 'invalid value for ' . $key => [
            self::replaceKey($perfectValues, $key, 5),
            self::createException('Expected a float. Got: integer'),
        ];
    }

    /**
     * @return iterable<string, array{0: array, 1: Exception}>
     */
    public static function array(array $perfectValues, string $key): iterable
    {
        yield from self::exist($perfectValues, $key);

        yield 'invalid type for ' . $key . ' value' => [
            self::replaceKey($perfectValues, $key, 5),
            self::createException('Expected an array. Got: integer'),
        ];
    }

    /**
     * @return iterable<array-key, array{0: array, 1: Exception}>
     */
    public static function arrayCollection(array $perfectValues): iterable
    {
        yield 'invalid value type' => [
            self::replaceKey($perfectValues, '0', 5),
            self::createException('Expected an array. Got: integer'),
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

    private static function createException(string $message): Exception
    {
        return new AssertionFailedException($message);
    }
}
