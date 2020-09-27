<?php

declare(strict_types=1);

namespace Psl\Encoding\Base64;

use Psl\Encoding\Exception;

use function base64_decode;
use function preg_match;

/**
 * Decode a base64-encoded string into raw binary.
 *
 * Base64 character set:
 *  [A-Z]      [a-z]      [0-9]      +     /
 *  0x41-0x5a, 0x61-0x7a, 0x30-0x39, 0x2b, 0x2f
 *
 * @psalm-pure
 *
 * @throws Exception\RangeException             If the encoded string contains characters outside
 *                                              the base64 characters range.
 */
function decode(string $base64): string
{
    if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $base64)) {
        throw new Exception\RangeException(
            'The given base64 string contains characters outside the base64 range.'
        );
    }

    return (string) @base64_decode($base64, true);
}
