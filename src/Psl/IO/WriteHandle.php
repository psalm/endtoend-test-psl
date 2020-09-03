<?php

declare(strict_types=1);

namespace Psl\IO;

/**
 * An interface for a writable Handle.
 */
interface WriteHandle extends Handle
{
    /**
     * An immediate unordered write.
     *
     * @returns int the number of bytes written on success
     *
     * @throws Exception\BlockingException if the handle is a socket or similar, and the write would block.
     */
    public function write(string $bytes): int;

    public function flush(): void;
}
