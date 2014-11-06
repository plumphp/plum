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

/**
 * ArrayReader
 *
 * @package   FlorianEc\Plum\Reader
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 */
class ArrayReader implements ReaderInterface
{
    /** @var array */
    private $data = [];

    /** @var int */
    private $position = 0;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Returns the current element.
     *
     * @return mixed
     */
    public function current()
    {
        return $this->data[$this->position];
    }

    /**
     * Moves the reader to the next element.
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * Returns the key of the current element.
     *
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Returns if the current iterator position is valid.
     *
     * @return bool
     */
    public function valid()
    {
        return isset($this->data[$this->position]);
    }

    /**
     * Rewinds the iterator.
     */
    public function rewind()
    {
        $this->position = 0;
    }
}
