<?php

declare(strict_types=1);

namespace Psl\DataStructure;

use Countable;
use Psl;

/**
 * An interface representing a stack data structure ( LIFO ).
 *
 * @template T
 *
 * @see https://en.wikipedia.org/wiki/Stack_(abstract_data_type)
 */
interface StackInterface extends Countable
{
    /**
     * Adds an item to the stack.
     *
     * @param T $item
     */
    public function push($item): void;

    /**
     * Retrieves, but does remove, the most recently added item that was not yet removed,
     * or returns null if this queue is empty.
     *
     * @return null|T
     */
    public function peek();

    /**
     * Retrieves and removes the most recently added item that was not yet removed,
     * or returns null if this queue is empty.
     *
     * @return null|T
     */
    public function pull();

    /**
     * Retrieve and removes the most recently added item that was not yet removed.
     *
     * @throws Psl\Exception\InvariantViolationException If the stack is empty.
     *
     * @return T
     */
    public function pop();

    /**
     * Count the items in the stack.
     */
    public function count(): int;
}
