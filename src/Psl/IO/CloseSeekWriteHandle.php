<?php

declare(strict_types=1);

namespace Psl\IO;

interface CloseSeekWriteHandle extends SeekWriteHandle, CloseWriteHandle, CloseSeekHandle
{
}
