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

use \Mockery as m;

/**
 * FinderReaderTest
 *
 * @package   Cocur\Plum\Reader
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 *
 * @group unit
 */
class FinderReaderTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Symfony\Component\Finder\Finder|\Mockery\MockInterface */
    private $finder;

    public function setUp()
    {
        $this->finder = m::mock('Symfony\Component\Finder\Finder');
    }

    /**
     * @test
     * @covers Cocur\Plum\Reader\FinderReader::getIterator()
     */
    public function getIteratorShouldReturnIterator()
    {
        $iterator = m::mock('\Iterator');
        $this->finder->shouldReceive('getIterator')->andReturn($iterator);
        $reader = new FinderReader($this->finder);

        $this->assertEquals($iterator, $reader->getIterator());
    }

    /**
     * @test
     * @covers Cocur\Plum\Reader\FinderReader::count()
     */
    public function countShouldReturnNumberOfFiles()
    {
        $this->finder->shouldReceive('count')->once()->andReturn(2);
        $reader = new FinderReader($this->finder);

        $this->assertEquals(2, $reader->count());
    }
}
