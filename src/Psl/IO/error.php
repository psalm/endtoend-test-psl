<?php

declare(strict_types=1);

namespace Psl\IO;

/**
 * Return the output handle for the current request.
 *
 * This should generally be used for sending data to clients. In CLI mode, this
 * is usually the process STDOUT.
 */
function error(): ?WriteHandle
{
    if (\php_sapi_name() === "cli") {
        return Internal\open('php://stderr', 'wb');
    }

    return null;
}
