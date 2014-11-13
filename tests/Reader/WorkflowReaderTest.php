<?php

namespace Cocur\Plum\Reader;

/**
 * WorkflowReaderTest
 *
 * @package   Cocur\Plum\Reader
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 * @group     unit
 */
class WorkflowReaderTest extends \PHPUnit_Framework_TestCase
{
    /** @var WorkflowReader */
    private $reader;

    public function setUp()
    {
        $this->reader = new WorkflowReader();
    }

    /**
     * @test
     * @covers Cocur\Plum\Reader\WorkflowReader::addItem()
     * @covers Cocur\Plum\Reader\WorkflowReader::getIterator()
     */
    public function addItemShouldAddItemsAndGetIteratorShouldReturnIterator()
    {
        $this->reader->addItem('foo');

        $iterator = $this->reader->getIterator();

        $this->assertInstanceOf('\ArrayIterator', $iterator);
        $this->assertEquals('foo', $iterator[0]);
    }

    /**
     * @test
     * @covers Cocur\Plum\Reader\WorkflowReader::addItem()
     * @covers Cocur\Plum\Reader\WorkflowReader::count()
     */
    public function addItemAddsItemToCount()
    {
        $this->reader->addItem('foo');

        $this->assertEquals(1, $this->reader->count());
    }
}
