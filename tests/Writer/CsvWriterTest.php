<?php

namespace Cocur\Plum\Writer;

use org\bovigo\vfs\vfsStream;

/**
 * CsvWriterTest
 *
 * @package   Cocur\Plum\Writer
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
     * @covers Cocur\Plum\Writer\CsvWriter::writeItem()
     * @covers Cocur\Plum\Writer\CsvWriter::prepare()
     * @covers Cocur\Plum\Writer\CsvWriter::finish()
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
     * @covers Cocur\Plum\Writer\CsvWriter::__construct()
     * @covers Cocur\Plum\Writer\CsvWriter::writeItem()
     * @covers Cocur\Plum\Writer\CsvWriter::prepare()
     * @covers Cocur\Plum\Writer\CsvWriter::finish()
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
     * @covers Cocur\Plum\Writer\CsvWriter::writeItem()
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
     * @covers Cocur\Plum\Writer\CsvWriter::finish()
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
