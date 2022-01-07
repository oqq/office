<?php

declare(strict_types=1);

namespace Oqq\Office\Util;

use Oqq\Office\Exception\AssertionFailedException;
use Webmozart\Assert\Assert;

final class Assertion extends Assert
{
    /**
     * @param string $message
     * @psalm-pure
     */
    protected static function reportInvalidArgument($message): void
    {
        throw new AssertionFailedException($message);
    }
}
