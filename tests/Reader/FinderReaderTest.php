<?php

/**
 * This file is part of florianeckerstorfer/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FlorianEc\Plum\Reader;

use \Mockery as m;

/**
 * FinderReaderTest
 *
 * @package   FlorianEc\Plum\Reader
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 *
 * @group unit
 */
class FinderReaderTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Symfony\Component\Finder\Finder */
    private $finder;

    /** @var \Iterator|\Mockery\MockInterface */
    private $iterator;

    public function setUp()
    {
        $this->iterator = m::mock('\Iterator');

        $this->finder = m::mock('Symfony\Component\Finder\Finder');
        $this->finder->shouldReceive('getIterator')->andReturn($this->iterator);
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Reader\FinderReader::current()
     */
    public function currentShouldReturnCurrentElement()
    {
        $file = m::mock('\Symfony\Component\Finder\SplFileInfo');
        $this->iterator->shouldReceive('current')->once()->andReturn($file);

        $reader = new FinderReader($this->finder);

        $this->assertEquals($file, $reader->current());
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Reader\FinderReader::next()
     */
    public function nextShouldAdvanceIterator()
    {
        $this->iterator->shouldReceive('next')->once();

        $reader = new FinderReader($this->finder);
        $reader->next();
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Reader\FinderReader::key()
     */
    public function keyShouldReturnKey()
    {
        $this->iterator->shouldReceive('key')->once()->andReturn(0);

        $reader = new FinderReader($this->finder);

        $this->assertEquals(0, $reader->key());
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Reader\FinderReader::valid()
     */
    public function validShouldReturnIfIteratorIsValid()
    {
        $this->iterator->shouldReceive('valid')->once()->andReturn(true);

        $reader = new FinderReader($this->finder);

        $this->assertTrue($reader->valid());
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Reader\FinderReader::rewind()
     */
    public function validShouldRewindIterator()
    {
        $this->iterator->shouldReceive('rewind')->once();

        $reader = new FinderReader($this->finder);
        $reader->rewind();
    }
}
 