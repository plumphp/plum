<?php

/**
 * This file is part of florianeckerstorfer/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FlorianEc\Plum;

/**
 * Result
 *
 * @package   FlorianEc\Plum
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
    public function __construct($readCount, $writeCount, array $exceptions = [])
    {
        $this->readCount  = $readCount;
        $this->writeCount = $writeCount;
        $this->exceptions = $exceptions;
    }

    /**
     * @return int
     */
    public function getReadCount()
    {
        return $this->readCount;
    }

    /**
     * @return int
     */
    public function getWriteCount()
    {
        return $this->writeCount;
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
