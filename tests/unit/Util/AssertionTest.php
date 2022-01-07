<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Util;

use Oqq\Office\Exception\AssertionFailedException;
use Oqq\Office\Util\Assertion;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Util\Assertion
 */
final class AssertionTest extends TestCase
{
    public function test_it_throws_lib_exception(): void
    {
        $this->expectException(AssertionFailedException::class);

        Assertion::string(0);
    }
}
