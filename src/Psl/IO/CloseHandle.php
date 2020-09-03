<?php

declare(strict_types=1);

namespace Psl\IO;

/**
 * A handle that is explicitly closeable.
 */
interface CloseHandle extends Handle
{
    /**
     * Close the handle
     */
    public function close(): void;
}
