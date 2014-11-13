<?php

/**
 * This file is part of florianeckerstorfer/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocur\Plum\Reader;

/**
 * ArrayReader
 *
 * @package   Cocur\Plum\Reader
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 */
class WorkflowReader implements ReaderInterface
{
    /** @var array */
    private $data = [];

    /**
     * @param mixed $item
     *
     * @return ArrayReader
     */
    public function addItem($item)
    {
        $this->data[] = $item;

        return $this;
    }

    /**
     * @return \Iterator|void
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * Returns the number of elements in the array.
     *
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }
}
