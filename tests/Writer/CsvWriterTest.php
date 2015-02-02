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
 * CsvWriterTest
 *
 * @package   Plum\Plum\Writer
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 * @group     unit
 */
class CsvWriterTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        vfsStream::setup('fixtures');
    }

    /**
     * @test
     * @covers Plum\Plum\Writer\CsvWriter::writeItem()
     * @covers Plum\Plum\Writer\CsvWriter::prepare()
     * @covers Plum\Plum\Writer\CsvWriter::finish()
     */
    public function writeItemWritesItemIntoFile()
    {
        $writer = new CsvWriter(vfsStream::url('fixtures/foo.csv'));
        $writer->prepare();
        $writer->writeItem(['col 1', 'col 2', 'col 3']);
        $writer->finish();

        $this->assertEquals("\"col 1\",\"col 2\",\"col 3\"\n", file_get_contents(vfsStream::url('fixtures/foo.csv')));
    }

    /**
     * @test
     * @covers Plum\Plum\Writer\CsvWriter::__construct()
     * @covers Plum\Plum\Writer\CsvWriter::writeItem()
     * @covers Plum\Plum\Writer\CsvWriter::prepare()
     * @covers Plum\Plum\Writer\CsvWriter::finish()
     */
    public function writeItemWritesItemWithCustomOptionsIntoFile()
    {
        $writer = new CsvWriter(vfsStream::url('fixtures/foo.csv'), ';', "'");
        $writer->prepare();
        $writer->writeItem(['col 1', 'col 2', 'col 3']);
        $writer->finish();

        $this->assertEquals("'col 1';'col 2';'col 3'\n", file_get_contents(vfsStream::url('fixtures/foo.csv')));
    }

    /**
     * @test
     * @covers Plum\Plum\Writer\CsvWriter::setHeader()
     * @covers Plum\Plum\Writer\CsvWriter::writeItem()
     * @covers Plum\Plum\Writer\CsvWriter::prepare()
     */
    public function writeItemWritesItemWithHeaderIntoFile()
    {
        $writer = new CsvWriter(vfsStream::url('fixtures/foo.csv'), ',', '"');
        $writer->setHeader(['col 1', 'col 2', 'col 3']);
        $writer->prepare();
        $writer->writeItem(['val 1', 'val 2', 'val 3']);
        $writer->finish();

        $this->assertEquals(
            "\"col 1\",\"col 2\",\"col 3\"\n\"val 1\",\"val 2\",\"val 3\"\n",
            file_get_contents(vfsStream::url('fixtures/foo.csv'))
        );
    }

    /**
     * @test
     * @covers Plum\Plum\Writer\CsvWriter::writeItem()
     */
    public function writeItemThrowsAnExceptionIfNoFileHandleExists()
    {
        $writer = new CsvWriter(vfsStream::url('fixtures/foo.csv'));

        try {
            $writer->writeItem(['col 1', 'col 2', 'col 3']);
            $this->assertTrue(false);
        } catch (\LogicException $e) {
            $this->assertTrue(true);
            $this->assertRegExp('/fixtures\/foo\.csv/', $e->getMessage());
        }
    }

    /**
     * @test
     * @covers Plum\Plum\Writer\CsvWriter::finish()
     */
    public function finishThrowsAnExceptionIfNoFileHandleExists()
    {
        $writer = new CsvWriter(vfsStream::url('fixtures/foo.csv'));

        try {
            $writer->finish();
            $this->assertTrue(false);
        } catch (\LogicException $e) {
            $this->assertTrue(true);
            $this->assertRegExp('/fixtures\/foo\.csv/', $e->getMessage());
        }
    }
}
