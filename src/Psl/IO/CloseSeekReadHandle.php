<?php

declare(strict_types=1);

namespace Psl\IO;

interface CloseSeekReadHandle extends SeekReadHandle, CloseReadHandle, CloseSeekHandle
{
}
