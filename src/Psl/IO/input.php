<?php

declare(strict_types=1);

namespace Psl\IO;

/**
 * Return the input handle for the current request.
 *
 * In CLI mode, this is likely STDIN; for HTTP requests, it may contain the
 * POST data, if any.
 */
function input(): ReadHandle
{
    if (\php_sapi_name() === "cli") {
        return Internal\open('php://stdin', 'rb');
    }

    return Internal\open('php://input', 'rb');
}
