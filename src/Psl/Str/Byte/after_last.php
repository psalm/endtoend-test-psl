<?php

declare(strict_types=1);

namespace Psl\Str\Byte;

use Psl;

/**
 * @throws Psl\Exception\InvariantViolationException If the $offset is out-of-bounds.
 * @throws Psl\Exception\InvariantViolationException If an invalid $encoding is provided.
 *
 * @psalm-pure
 */
function after_last(
    string $haystack,
    string $needle,
    int $offset = 0
): ?string {
    $offset = search_last($haystack, $needle, $offset);
    if (null === $offset) {
        return null;
    }

        $offset += length($needle);

    return slice($haystack, $offset, null);
}
