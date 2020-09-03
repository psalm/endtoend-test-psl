<?php

declare(strict_types=1);

namespace Psl\Internal;

use function error_reporting;

/**
 * @psalm-template T
 *
 * @psalm-param (callable(): T) $fun
 *
 * @psalm-return T
 */
function suppress(callable $fun)
{
    $level = error_reporting(0);

    $result = $fun();

    error_reporting($level);

    return $result;
}