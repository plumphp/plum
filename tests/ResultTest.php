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

use \Mockery as m;

/**
 * ResultTest
 *
 * @package   Cocur\Plum
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
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
     * @covers Cocur\Plum\Result::incReadCount()
     * @covers Cocur\Plum\Result::getReadCount()
     */
    public function incReadCountShouldIncreaseReadCount()
    {
        $this->result->incReadCount();

        $this->assertEquals(1, $this->result->getReadCount());
    }

    /**
     * @test
     * @covers Cocur\Plum\Result::incWriteCount()
     * @covers Cocur\Plum\Result::getWriteCount()
     */
    public function incWriteCountShouldIncreaseWriteCount()
    {
        $this->result->incWriteCount();

        $this->assertEquals(1, $this->result->getWriteCount());
    }

    /**
     * @test
     * @covers Cocur\Plum\Result::incItemWriteCount()
     * @covers Cocur\Plum\Result::getItemWriteCount()
     */
    public function incItemWriteCountShouldIncreaseWriteCount()
    {
        $this->result->incItemWriteCount();

        $this->assertEquals(1, $this->result->getItemWriteCount());
    }

    /**
     * @test
     * @covers Cocur\Plum\Result::addException()
     * @covers Cocur\Plum\Result::getExceptions()
     * @covers Cocur\Plum\Result::getErrorCount()
     */
    public function addExceptionShouldAddException()
    {
        $exception = new \Exception();
        $this->result->addException($exception);

        $this->assertContains($exception, $this->result->getExceptions());
        $this->assertEquals(1, $this->result->getErrorCount());
    }
}
