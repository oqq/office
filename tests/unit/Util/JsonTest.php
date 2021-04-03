<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Util;

use JsonException;
use Oqq\Office\Util\Json;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Util\Json
 */
final class JsonTest extends TestCase
{
    public function test_encode_throws_exception(): void
    {
        $this->expectException(JsonException::class);

        Json::encode(\fopen('php://memory', 'rb'), \JSON_FORCE_OBJECT);
    }

    public function test_encode_encodes(): void
    {
        Assert::assertSame('["test"]', Json::encode(['test']));
        Assert::assertSame('{"0":"test"}', Json::encode(['test'], \JSON_FORCE_OBJECT));
    }

    public function test_decode_throws_exception(): void
    {
        $this->expectException(JsonException::class);

        Json::decode('invalid json', \JSON_BIGINT_AS_STRING);
    }

    public function test_decode_decodes(): void
    {
        Assert::assertSame(
            ['foo' => 12345678901234567890, 'bar' => 'baz'],
            Json::decode('{"foo": 12345678901234567890, "bar": "baz"}')
        );

        Assert::assertSame(
            ['foo' => '12345678901234567890', 'bar' => 'baz'],
            Json::decode('{"foo": 12345678901234567890, "bar": "baz"}', \JSON_BIGINT_AS_STRING)
        );
    }
}
