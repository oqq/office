<?php

declare(strict_types=1);

namespace Oqq\Office\Exception;

use InvalidArgumentException;

final class AssertionFailedException extends InvalidArgumentException implements OfficeException
{
}
