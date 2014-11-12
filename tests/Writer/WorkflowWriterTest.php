<?php


namespace Cocur\Plum\Writer;

use \Mockery as m;

/**
 * WorkflowWriterTest
 *
 * @package   Cocur\Plum\Writer
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 * @group     unit
 */
class WorkflowWriterTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Cocur\Plum\Reader\WorkflowReader|\Mockery\MockInterface */
    private $reader;

    /** @var WorkflowWriter */
    private $writer;

    public function setUp()
    {
        $this->reader = m::mock('Cocur\Plum\Reader\WorkflowReader');
        $this->writer = new WorkflowWriter($this->reader);
    }

    /**
     * @test
     * @covers Cocur\Plum\Writer\WorkflowWriter::__construct()
     * @covers Cocur\Plum\Writer\WorkflowWriter::writeItem()
     */
    public function writeItemAddsItemToReader()
    {
        $this->reader->shouldReceive('addItem')->once();

        $this->writer->writeItem('foo');
    }

    /**
     * @test
     * @covers Cocur\Plum\Writer\WorkflowWriter::prepare()
     */
    public function prepareDoesNothing()
    {
        $this->writer->prepare();
    }

    /**
     * @test
     * @covers Cocur\Plum\Writer\WorkflowWriter::finish()
     */
    public function finishDoesNothing()
    {
        $this->writer->finish();
    }
}
