<?php

declare(strict_types=1);

namespace Psl\IO\Exception;

use Psl\Exception\RuntimeException;

final class BlockingException extends RuntimeException implements ExceptionInterface
{
}