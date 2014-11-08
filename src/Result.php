<?php

/**
 * This file is part of florianeckerstorfer/plum.
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
    private $readCount;

    /** @var int */
    private $writeCount;

    /** @var \Exception[] */
    private $exceptions;

    /**
     * @param int          $readCount
     * @param int          $writeCount
     * @param \Exception[] $exceptions
     */
    public function __construct($readCount = 0, $writeCount = 0, array $exceptions = [])
    {
        $this->readCount  = $readCount;
        $this->writeCount = $writeCount;
        $this->exceptions = $exceptions;
    }

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
     * @return int
     */
    public function getWriteCount()
    {
        return $this->writeCount;
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
