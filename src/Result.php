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

/**
 * Result
 *
 * @package   Cocur\Plum
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 */
class Result
{
    /** @var int */
    private $readCount = 0;

    /** @var int */
    private $writeCount = 0;

    /** @var int */
    private $itemWriteCount = 0;

    /** @var \Exception[] */
    private $exceptions;

    /**
     * @return Result
     */
    public function incReadCount()
    {
        $this->readCount++;

        return $this;
    }

    /**
     * @return int
     */
    public function getReadCount()
    {
        return $this->readCount;
    }

    /**
     * @return Result
     */
    public function incWriteCount()
    {
        $this->writeCount++;

        return $this;
    }

    /**
     * Returns the write count of the result. This counter is incremented every time an item is written to a writer. If
     * an item is written to multiple writers, the counter is increased multiple times for every item. For example, when
     * 3 items are written to 2 writers each then the write count will be 6.
     *
     * @return int
     */
    public function getWriteCount()
    {
        return $this->writeCount;
    }

    /**
     * @return Result
     */
    public function incItemWriteCount()
    {
        $this->itemWriteCount++;

        return $this;
    }

    /**
     * Returns the item write count of the result. This counter is incremented every time an item is written. Every
     * item can only increase this counter by 1. For example, when 3 items are written to 2 writers each then the
     * item write count will be 3.
     *
     * @return int
     */
    public function getItemWriteCount()
    {
        return $this->itemWriteCount;
    }

    /**
     * @param \Exception $exception
     *
     * @return Result
     */
    public function addException(\Exception $exception)
    {
        $this->exceptions[] = $exception;

        return $this;
    }

    /**
     * @return int
     */
    public function getErrorCount()
    {
        return count($this->exceptions);
    }

    /**
     * @return \Exception[]
     */
    public function getExceptions()
    {
        return $this->exceptions;
    }
}
