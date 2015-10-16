<?php

/**
 * This file is part of plumphp/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plum\Plum\Reader;

use ArrayIterator;

/**
 * ArrayReader
 *
 * @package   Plum\Plum\Reader
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014-2015 Florian Eckerstorfer
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
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->data);
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

    /**
     * @param mixed $input
     *
     * @return bool `true` if the reader accepts the given input, `false` if not
     */
    public static function accepts($input)
    {
        return is_array($input);
    }
}
