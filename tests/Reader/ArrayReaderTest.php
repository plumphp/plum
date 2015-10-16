<?php

/**
 * This file is part of plumphp/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plum\Plum\Reader;

/**
 * ArrayReaderTest
 *
 * @package   Plum\Plum\Reader
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014-2015 Florian Eckerstorfer
 * @group     unit
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
     * @covers Plum\Plum\Reader\ArrayReader::__construct()
     * @covers Plum\Plum\Reader\ArrayReader::getData()
     */
    public function getDataShouldReturnData()
    {
        $this->assertEquals(['foobar'], $this->reader->getData());
    }

    /**
     * @test
     * @covers Plum\Plum\Reader\ArrayReader::getIterator()
     */
    public function getIteratorShouldReturnArrayIterator()
    {
        $iterator = $this->reader->getIterator();

        $this->assertInstanceOf('\ArrayIterator', $iterator);
        $this->assertEquals('foobar', $iterator[0]);
    }

    /**
     * @test
     * @covers Plum\Plum\Reader\ArrayReader::count()
     */
    public function countShouldReturnNumberOfElements()
    {
        $this->assertEquals(1, $this->reader->count());
    }

    /**
     * @test
     * @covers Plum\Plum\Reader\ArrayReader::accepts()
     */
    public function acceptsReturnsTrueIfInputIsArray()
    {
        $this->assertTrue($this->reader->accepts(['foo']));
    }

    /**
     * @test
     * @covers Plum\Plum\Reader\ArrayReader::accepts()
     */
    public function acceptsReturnsFalseIfInputIsNotAnArray()
    {
        $this->assertFalse(ArrayReader::accepts('foo'));
    }
}
