<?php

declare(strict_types=1);

namespace Psl\IO;

/**
 * A handle that can have its' position changed.
 */
interface SeekHandle extends Handle
{
    /**
     * Move to a specific offset within a handle.
     *
     * Offset is relative to the start of the handle - so, the beginning of the
     * handle is always offset 0.
     */
    public function seek(int $offset): void;

    /**
     * Get the current pointer position within a handle.
     */
    public function tell(): int;
}
