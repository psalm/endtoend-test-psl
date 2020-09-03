<?php

declare(strict_types=1);

namespace Psl\IO;

interface CloseSeekReadWriteHandle extends SeekReadWriteHandle, CloseReadWriteHandle, CloseSeekWriteHandle, CloseSeekReadHandle
{
}
