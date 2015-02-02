<?php

/**
 * This file is part of plumphp/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plum\Plum\Writer;

/**
 * ArrayWriter
 *
 * @package   Plum\Plum\Writer
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 */
class ArrayWriter implements WriterInterface
{
    /** @var array */
    private $data = [];

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Writes the given item to the array.
     *
     * @param $item
     */
    public function writeItem($item)
    {
        $this->data[] = $item;
    }

    public function prepare()
    {
    }

    public function finish()
    {
    }
}
