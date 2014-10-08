<?php


namespace FlorianEc\Plum\Writer;

/**
 * ArrayWriterTest
 *
 * @package   FlorianEc\Plum\Writer
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 *
 * @group unit
 */
class ArrayWriterTest extends \PHPUnit_Framework_TestCase
{
    /** @var ArrayWriter */
    private $writer;

    public function setUp()
    {
        $this->writer = new ArrayWriter();
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Writer\ArrayWriter::writeItem()
     * @covers FlorianEc\Plum\Writer\ArrayWriter::getData()
     */
    public function writeItemShouldWriteItemToArray()
    {
        $this->writer->writeItem('foobar');

        $this->assertContains('foobar', $this->writer->getData());
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Writer\ArrayWriter::prepare()
     */
    public function prepareShouldDoNothing()
    {
        $this->writer->prepare();
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Writer\ArrayWriter::finish()
     */
    public function finishShouldDoNothing()
    {
        $this->writer->finish();
    }
}
 