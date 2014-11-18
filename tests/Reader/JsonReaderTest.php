<?php

/**
 * This file is part of cocur/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocur\Plum\Reader;

use org\bovigo\vfs\vfsStream;

/**
 * JsonReaderTest
 *
 * @package   Cocur\Plum\Reader
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 * @group     unit
 */
class JsonReaderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        vfsStream::setup('fixtures', null, [
            'foo.json' => '[{"foo": "bar"}]'
        ]);
    }

    /**
     * @test
     * @covers Cocur\Plum\Reader\JsonReader::__construct()
     * @covers Cocur\Plum\Reader\JsonReader::getIterator()
     * @covers Cocur\Plum\Reader\JsonReader::getJson()
     */
    public function getIteratorReturnsIteratorOfJsonFile()
    {
        $reader = new JsonReader(vfsStream::url('fixtures/foo.json'));

        $this->assertEquals('bar', $reader->getIterator()[0]['foo']);
    }

    /**
     * @test
     * @covers Cocur\Plum\Reader\JsonReader::__construct()
     * @covers Cocur\Plum\Reader\JsonReader::count()
     * @covers Cocur\Plum\Reader\JsonReader::getJson()
     */
    public function countReturnsNumberOfItemsInJsonFile()
    {
        $reader = new JsonReader(vfsStream::url('fixtures/foo.json'));

        $this->assertEquals(1, $reader->count());
    }
}
