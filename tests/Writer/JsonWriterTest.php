<?php

/**
 * This file is part of plumphp/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plum\Plum\Writer;

use org\bovigo\vfs\vfsStream;

/**
 * JsonWriterTest
 *
 * @package   Plum\Plum\Writer
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 * @group     unit
 */
class JsonWriterTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        vfsStream::setup('fixtures');
    }

    /**
     * @test
     * @covers Plum\Plum\Writer\JsonWriter::__construct()
     * @covers Plum\Plum\Writer\JsonWriter::writeItem()
     * @covers Plum\Plum\Writer\JsonWriter::finish()
     */
    public function writeItemWritesItemAsJsonToFile()
    {
        $writer = new JsonWriter(vfsStream::url('fixtures/foo.json'));
        $writer->writeItem(['key1' => 'value1', 'key2' => 'value2']);
        $writer->finish();

        $json = json_decode(file_get_contents(vfsStream::url('fixtures/foo.json')), true);
        $this->assertEquals('value1', $json[0]['key1']);
        $this->assertEquals('value2', $json[0]['key2']);
    }

    /**
     * @test
     * @covers Plum\Plum\Writer\JsonWriter::prepare()
     */
    public function prepareDoesNothing()
    {
        $writer = new JsonWriter('foo.json');
        $writer->prepare();
    }
}
