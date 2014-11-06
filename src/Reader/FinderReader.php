<?php

/**
 * This file is part of florianeckerstorfer/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FlorianEc\Plum\Reader;

use Symfony\Component\Finder\Finder;

/**
 * FinderReader
 *
 * @package   FlorianEc\Plum\Reader
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 */
class FinderReader implements ReaderInterface
{
    /** @var \Iterator */
    private $iterator;

    /**
     * @param Finder $finder
     *
     * @codeCoverageIgnore
     */
    public function __construct(Finder $finder)
    {
        $this->iterator = $finder->getIterator();
    }

    /**
     * Return the current element.
     *
     * @return \Symfony\Component\Finder\SplFileInfo
     */
    public function current()
    {
        return $this->iterator->current();
    }

    /**
     * Move forward to next element.
     */
    public function next()
    {
        $this->iterator->next();
    }

    /**
     * Return the key of the current element.
     *
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->iterator->key();
    }

    /**
     * Checks if current position is valid
     *
     * @return bool `true` if the iterator is valid.
     */
    public function valid()
    {
        return $this->iterator->valid();
    }

    /**
     * Rewind the Iterator to the first element
     */
    public function rewind()
    {
        $this->iterator->rewind();
    }
}
