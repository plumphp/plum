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
use Traversable;

/**
 * ArrayReader
 *
 * @package   Cocur\Plum\Reader
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 */
class ArrayReader implements ReaderInterface
{
    /** @var array */
    private $data = [];

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
     * @return Traversable|void
     */
    public function getIterator()
    {
        foreach ($this->data as $key => $value) {
            yield $key => $value;
        }
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
