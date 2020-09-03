<?php

declare(strict_types=1);

namespace Psl\IO;

interface SeekReadWriteHandle extends ReadWriteHandle, SeekWriteHandle, SeekReadHandle
{
}
