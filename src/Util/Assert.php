<?php

declare(strict_types=1);

namespace Oqq\Office\Util;

use Oqq\Office\Exception\InvalidArgumentException;

final class Assert extends \Webmozart\Assert\Assert
{
    /**
     * @param string $message
     * @psalm-pure
     */
    protected static function reportInvalidArgument($message): void
    {
        throw new InvalidArgumentException($message);
    }
}
