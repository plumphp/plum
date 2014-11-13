<?php

/**
 * This file is part of cocur/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocur\Plum\Writer;

use Cocur\Plum\Reader\ArrayReader;
use Cocur\Plum\Reader\WorkflowReader;

/**
 * WorkflowWriter
 *
 * @package   Cocur\Plum\Writer
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 */
class WorkflowWriter implements WriterInterface
{
    /** @var WorkflowReader */
    private $reader;

    /**
     * @param WorkflowReader $reader
     */
    public function __construct(WorkflowReader $reader)
    {
        $this->reader = $reader;
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
        $this->reader->addItem($item);
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
