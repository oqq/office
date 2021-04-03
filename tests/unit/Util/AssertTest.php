<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Util;

use Oqq\Office\Exception\InvalidArgumentException;
use Oqq\Office\Util\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Util\Assert
 */
final class AssertTest extends TestCase
{
    public function test_it_throws_lib_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Assert::string(0);
    }
}
