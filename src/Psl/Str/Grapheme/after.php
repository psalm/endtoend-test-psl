<?php

declare(strict_types=1);

namespace Psl\Str\Grapheme;

use Psl;
use Psl\Str\Exception;

/**
 * @throws Exception\OutOfBoundsException If the $offset is out-of-bounds.
 * @throws Psl\Exception\InvariantViolationException If unable to split $string into grapheme clusters.
 *
 * @pure
 */
function after(string $haystack, string $needle, int $offset = 0): ?string
{
    $position = search($haystack, $needle, $offset);
    if (null === $position) {
        return null;
    }

    $position += length($needle);

    return slice($haystack, $position);
}
