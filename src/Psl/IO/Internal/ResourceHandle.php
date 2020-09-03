<?php

namespace Psl\IO\Internal;

use Psl;
use Psl\Internal;
use Psl\IO\CloseSeekReadWriteHandle;
use Psl\Type;
use function error_clear_last;
use function error_get_last;
use function fclose;
use function fseek;
use function ftell;
use function fwrite;

final class ResourceHandle implements CloseSeekReadWriteHandle
{
    /**
     * @var resource|null $resource
     */
    private $resource;

    public function __construct($resource)
    {
        $resource = Type\resource()->assert($resource);
        stream_set_blocking($resource, false);

        $this->resource = $resource;
    }

    public function write(string $bytes): int
    {
        return $this->box(function () use ($bytes) {
            $metadata = stream_get_meta_data($this->resource);
            if ($metadata['blocking']) {
                throw new Psl\IO\Exception\BlockingException('The stream is blocking.');
            }

            $result = fwrite($this->resource, $bytes);
            if ($result === false) {
                $err = error_get_last();

                Psl\invariant_violation('error(%d): %s.', $err['type'], $err['message']);
            }

            return $result;
        });
    }

    public function seek(int $offset): void
    {
        $this->box(function () use ($offset) {
            $metadata = stream_get_meta_data($this->resource);
            Psl\invariant($metadata['seekable'], 'Stream is not seekable.');

            fseek($this->resource, $offset);
        });
    }

    public function tell(): int
    {
        return $this->box(function () {
            $metadata = stream_get_meta_data($this->resource);
            Psl\invariant($metadata['seekable'], 'Stream is not seekable.');

            return ftell($this->resource);
        });
    }

    public function read(?int $max_bytes = null): string
    {
        return $this->box(function () use ($max_bytes) {
            Psl\invariant($max_bytes === null || $max_bytes > 0, '$max_bytes must be null, or > 0');
            $metadata = stream_get_meta_data($this->resource);
            if ($metadata['blocking']) {
                throw new Psl\IO\Exception\BlockingException('The stream is blocking.');
            }

            if (null !== $max_bytes && null !== $metadata['unread_data'] && $metadata['unread_data'] < $max_bytes) {
                $max_bytes = $metadata['unread_data'];
            }

            $result = stream_get_contents($this->resource, $max_bytes ?? -1);
            if ($result === false) {
                $err = error_get_last();

                Psl\invariant_violation('error(%d): %s.', $err['type'], $err['message']);
            }

            return $result;
        });
    }

    public function flush(): void
    {
        $this->box(function () {
            $result = fflush($this->resource);
            if ($result === false) {
                $err = error_get_last();

                Psl\invariant_violation('error(%d): %s.', $err['type'], $err['message']);
            }
        });
    }

    public function close(): void
    {
        $this->box(function () {
            $result = fclose($this->resource);
            if ($result === false) {
                $err = error_get_last();

                Psl\invariant_violation('error(%d): %s.', $err['type'], $err['message']);
            }

            $this->resource = null;
        });
    }

    /**
     * @psalm-template T
     *
     * @psalm-param (callable(): T) $fun
     *
     * @psalm-return T
     */
    public function box(callable $fun)
    {
        /** @psalm-suppress MissingThrowsDocblock */
        Psl\invariant(null !== $this->resource, 'Stream is already closed.');

        error_clear_last();

        Psl\invariant(Type\is_resource($this->resource), 'Invalid handle.');

        return Internal\suppress($fun);
    }
}