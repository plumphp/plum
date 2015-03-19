<?php

/**
 * This file is part of plumphp/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plum\Plum;

use \Mockery as m;

/**
 * ResultTest
 *
 * @package   Plum\Plum
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014-2015 Florian Eckerstorfer
 * @group     unit
 */
class ResultTest extends \PHPUnit_Framework_TestCase
{
    /** @var Result */
    private $result;

    public function setUp()
    {
        $this->result = new Result();
    }

    /**
     * @test
     * @covers Plum\Plum\Result::incReadCount()
     * @covers Plum\Plum\Result::getReadCount()
     */
    public function incReadCountShouldIncreaseReadCount()
    {
        $this->result->incReadCount();

        $this->assertEquals(1, $this->result->getReadCount());
    }

    /**
     * @test
     * @covers Plum\Plum\Result::incWriteCount()
     * @covers Plum\Plum\Result::getWriteCount()
     */
    public function incWriteCountShouldIncreaseWriteCount()
    {
        $this->result->incWriteCount();

        $this->assertEquals(1, $this->result->getWriteCount());
    }

    /**
     * @test
     * @covers Plum\Plum\Result::incItemWriteCount()
     * @covers Plum\Plum\Result::getItemWriteCount()
     */
    public function incItemWriteCountShouldIncreaseWriteCount()
    {
        $this->result->incItemWriteCount();

        $this->assertEquals(1, $this->result->getItemWriteCount());
    }

    /**
     * @test
     * @covers Plum\Plum\Result::addException()
     * @covers Plum\Plum\Result::getExceptions()
     * @covers Plum\Plum\Result::getErrorCount()
     */
    public function addExceptionShouldAddException()
    {
        $exception = new \Exception();
        $this->result->addException($exception);

        $this->assertContains($exception, $this->result->getExceptions());
        $this->assertEquals(1, $this->result->getErrorCount());
    }
}
