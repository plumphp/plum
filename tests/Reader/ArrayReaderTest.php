<?php

/**
 * This file is part of florianeckerstorfer/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocur\Plum\Reader;

/**
 * ArrayReaderTest
 *
 * @package   Cocur\Plum\Reader
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 *
 * @group unit
 */
class ArrayReaderTest extends \PHPUnit_Framework_TestCase
{
    /** @var ArrayReader */
    private $reader;

    public function setUp()
    {
        $this->reader = new ArrayReader(['foobar']);
    }

    /**
     * @test
     * @covers Cocur\Plum\Reader\ArrayReader::__construct()
     * @covers Cocur\Plum\Reader\ArrayReader::getData()
     */
    public function getDataShouldReturnData()
    {
        $this->assertEquals(['foobar'], $this->reader->getData());
    }

    /**
     * @test
     * @covers Cocur\Plum\Reader\ArrayReader::current()
     */
    public function currentShouldReturnCurrentItem()
    {
        $this->assertEquals('foobar', $this->reader->current());
    }

    /**
     * @test
     * @covers Cocur\Plum\Reader\ArrayReader::key()
     */
    public function keyShouldReturnCurrentKey()
    {
        $this->assertEquals(0, $this->reader->key());
    }

    /**
     * @test
     * @covers Cocur\Plum\Reader\ArrayReader::next()
     */
    public function nextShouldMoveIteratorToNextPosition()
    {
        $this->reader->next();
        $this->assertEquals(1, $this->reader->key());
    }

    /**
     * @test
     * @covers Cocur\Plum\Reader\ArrayReader::valid()
     */
    public function validShouldReturnTrueIfPositionIsValid()
    {
        $this->assertTrue($this->reader->valid());
    }

    /**
     * @test
     * @covers Cocur\Plum\Reader\ArrayReader::valid()
     */
    public function validShouldReturnFalseIfPositionIsInvalid()
    {
        $this->reader->next();
        $this->assertFalse($this->reader->valid());
    }

    /**
     * @test
     * @covers Cocur\Plum\Reader\ArrayReader::rewind()
     */
    public function rewindShouldRewindIterator()
    {
        $this->reader->next();
        $this->reader->rewind();
        $this->assertEquals(0, $this->reader->key());
    }

    /**
     * @test
     * @covers Cocur\Plum\Reader\ArrayReader::count()
     */
    public function countShouldReturnNumberOfElements()
    {
        $this->assertEquals(1, $this->reader->count());
    }
}
