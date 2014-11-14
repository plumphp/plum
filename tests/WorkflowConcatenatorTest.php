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
 * WorkflowConcatenatorTest
 *
 * @package   Cocur\Plum
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 * @group     unit
 */
class WorkflowConcatenatorTest extends \PHPUnit_Framework_TestCase
{
    /** @var WorkflowConcatenator */
    private $concatenator;

    public function setUp()
    {
        $this->concatenator = new WorkflowConcatenator();
    }

    /**
     * @test
     * @covers Cocur\Plum\WorkflowConcatenator::getIterator()
     */
    public function getIteratorReturnsAnIterator()
    {
        $this->assertInstanceOf('\ArrayIterator', $this->concatenator->getIterator());
    }

    /**
     * @test
     * @covers Cocur\Plum\WorkflowConcatenator::count()
     */
    public function countReturnsTheNumberOfItems()
    {
        $this->assertEquals(0, $this->concatenator->count());
        $this->concatenator->writeItem('foo');
        $this->assertEquals(1, $this->concatenator->count());
    }

    /**
     * @test
     * @covers Cocur\Plum\WorkflowConcatenator::writeItem()
     */
    public function writeItemAddsItem()
    {
        $this->concatenator->writeItem('foo');
        $this->assertEquals(1, $this->concatenator->count());
    }

    /**
     * @test
     * @covers Cocur\Plum\WorkflowConcatenator::prepare()
     */
    public function prepareDoesNothing()
    {
        $this->concatenator->prepare();
    }

    /**
     * @test
     * @covers Cocur\Plum\WorkflowConcatenator::finish()
     */
    public function finishDoesNothing()
    {
        $this->concatenator->finish();
    }
}
