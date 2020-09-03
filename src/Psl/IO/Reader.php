<?php

declare(strict_types=1);

namespace Psl\IO;

use Psl;
use Psl\Str;

final class Reader implements ReadHandle
{
    private ReadHandle $handle;

    private bool    $eof = false;
    private string  $buffer = '';

    public function __construct(ReadHandle $handle)
    {
        $this->handle = $handle;
    }

    public function getHandle(): ReadHandle
    {
        return $this->handle;
    }

    public function read(?int $max_bytes = null): string
    {
        Psl\invariant($max_bytes === null || $max_bytes >= 0, '$max_bytes must be null, or >= 0');

        if ($this->eof) {
            return '';
        }

        if ($this->buffer === '') {
            $this->buffer = $this->handle->read();
        }

        if ($this->buffer === '') {
            $this->eof = true;

            return '';
        }

        if (null === $max_bytes || $max_bytes >= Str\Byte\length($this->buffer)) {
            $buffer = $this->buffer;
            $this->buffer = '';

            return $buffer;
        }

        $buffer = $this->buffer;
        $this->buffer = Str\Byte\slice($buffer, $max_bytes);

        return Str\Byte\slice($buffer, 0, $max_bytes);
    }

    public function readUntil(string $suffix): ?string
    {
        $buf = $this->buffer;
        $idx = Str\Byte\search($buf, $suffix);
        $suffix_len = Str\Byte\length($suffix);
        if ($idx !== null) {
            $this->buffer = Str\Byte\slice($buf, $idx + $suffix_len);
            return Str\Byte\slice($buf, 0, $idx);
        }

        do {
            $chunk = $this->handle->read();
            if ($chunk === '') {
                $this->buffer = $buf;
                return null;
            }
            $buf .= $chunk;
        } while (!Str\Byte\contains($chunk, $suffix));

        $idx = Str\Byte\search($buf, $suffix);
        Psl\invariant($idx !== null, 'Should not have exited loop without suffix');
        $this->buffer = Str\Byte\slice($buf, $idx + $suffix_len);
        return Str\Byte\slice($buf, 0, $idx);
    }

    public function readFixedSize(int $size): string
    {
        while (Str\Byte\length($this->buffer) < $size && !$this->eof) {
            $chunk = $this->getHandle()->read($size - Str\Byte\length($this->buffer));
            if ($chunk === '') {
                $this->eof = true;
            }

            $this->buffer .= $chunk;
        }

        Psl\invariant(!$this->eof, 'Reached end of file before requested size');
        $buffer_size = Str\Byte\length($this->buffer);
        Psl\invariant($buffer_size >= $size, 'Should have read the requested data or reached EOF');
        if ($size === $buffer_size) {
            $ret = $this->buffer;
            $this->buffer = '';
            return $ret;
        }

        $ret = Str\Byte\slice($this->buffer, 0, $size);
        $this->buffer = Str\Byte\slice($this->buffer, $size);
        return $ret;
    }

    public function readByte(): string
    {
        return $this->readFixedSize(1);
    }

    public function readLine(): ?string
    {
        $line = $this->readUntil(PHP_EOL);
        if (null !== $line) {
            return $line;
        }

        $content = $this->read();
        return '' === $content ? null : $content;
    }

    public function isEndOfFile(): bool
    {
        if ($this->eof) {
            return true;
        }

        if ($this->buffer !== '') {
            return false;
        }

        $this->buffer = $this->handle->read();
        if ($this->buffer === '') {
            $this->eof = true;
            return true;
        }

        return false;
    }
}