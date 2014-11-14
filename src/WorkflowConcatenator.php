<?php

/**
 * This file is part of cocur/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocur\Plum;


use Cocur\Plum\Reader\ReaderInterface;
use Cocur\Plum\Writer\WriterInterface;

/**
 * WorkflowConcatenator
 *
 * @package   Cocur\Plum
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 */
class WorkflowConcatenator implements ReaderInterface, WriterInterface
{
    /** @var mixed[] */
    private $data = [];

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * Write the given item.
     *
     * @param mixed $item
     *
     * @return void
     */
    public function writeItem($item)
    {
        $this->data[] = $item;
    }

    /**
     * Prepare the writer.
     *
     * @return void
     */
    public function prepare()
    {
    }

    /**
     * Finish the writer.
     *
     * @return void
     */
    public function finish()
    {
    }
}
