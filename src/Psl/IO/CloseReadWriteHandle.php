<?php

declare(strict_types=1);

namespace Psl\IO;

interface CloseReadWriteHandle extends ReadWriteHandle, CloseWriteHandle, CloseReadHandle
{
}
