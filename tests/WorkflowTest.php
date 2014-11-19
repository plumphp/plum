<?php

/**
 * This file is part of cocur/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocur\Plum;

use \Mockery as m;

/**
 * WorkflowTest
 *
 * @package   Cocur\Plum
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 *
 * @group unit
 */
class WorkflowTest extends \PHPUnit_Framework_TestCase
{
    /** @var Workflow */
    private $workflow;

    public function setUp()
    {
        $this->workflow = new Workflow();
    }

    /**
     * @test
     * @covers Cocur\Plum\Workflow::getPipeline()
     */
    public function getPipelineShouldReturnAllPipelineElements()
    {
        $filter    = $this->getMockFilter();
        $converter = $this->getMockConverter();

        $this->workflow->addFilter($filter);
        $this->workflow->addConverter($converter);

        $this->assertCount(2, $this->workflow->getPipeline());
    }

    /**
     * @test
     * @covers Cocur\Plum\Workflow::getPipeline()
     */
    public function getPipelineShouldReturnOnlyElementsOfGivenType()
    {
        $filter    = $this->getMockFilter();
        $converter = $this->getMockConverter();

        $this->workflow->addFilter($filter);
        $this->workflow->addConverter($converter);

        $this->assertCount(1, $this->workflow->getPipeline(Workflow::PIPELINE_TYPE_FILTER));
    }

    /**
     * @test
     * @covers Cocur\Plum\Workflow::addElement()
     * @covers Cocur\Plum\Workflow::addFilter()
     * @covers Cocur\Plum\Workflow::getFilters()
     */
    public function addFilterShouldAddFilterToWorkflow()
    {
        $filter = $this->getMockFilter();
        $this->workflow->addFilter($filter);

        $this->assertContains($filter, $this->workflow->getFilters());
    }

    /**
     * @test
     * @covers Cocur\Plum\Workflow::addElement()
     * @covers Cocur\Plum\Workflow::addFilter()
     * @covers Cocur\Plum\Workflow::getFilters()
     */
    public function addFilterWithPrependShouldPrependFilterToWorkflow()
    {
        $filter1 = $this->getMockFilter();
        $filter2 = $this->getMockFilter();
        $this->workflow->addFilter($filter1);
        $this->workflow->addFilter($filter2, Workflow::PREPEND);

        $this->assertSame($filter2, $this->workflow->getFilters()[0]);
        $this->assertSame($filter1, $this->workflow->getFilters()[1]);
    }

    /**
     * @test
     * @covers Cocur\Plum\Workflow::addElement()
     * @covers Cocur\Plum\Workflow::addConverter()
     * @covers Cocur\Plum\Workflow::getConverters()
     */
    public function addConverterShouldAddConverterToWorkflow()
    {
        $converter = $this->getMockConverter();
        $this->workflow->addConverter($converter);

        $this->assertContains($converter, $this->workflow->getConverters());
    }

    /**
     * @test
     * @covers Cocur\Plum\Workflow::addElement()
     * @covers Cocur\Plum\Workflow::addConverter()
     * @covers Cocur\Plum\Workflow::getConverters()
     */
    public function addConverterWithPrependShouldPrependConverterToWorkflow()
    {
        $converter1 = $this->getMockConverter();
        $converter2 = $this->getMockConverter();
        $this->workflow->addConverter($converter1);
        $this->workflow->addConverter($converter2, null, Workflow::PREPEND);

        $this->assertSame($converter2, $this->workflow->getConverters()[0]);
        $this->assertSame($converter1, $this->workflow->getConverters()[1]);
    }

    /**
     * @test
     * @covers Cocur\Plum\Workflow::addElement()
     * @covers Cocur\Plum\Workflow::addWriter()
     * @covers Cocur\Plum\Workflow::getWriters()
     */
    public function addWriterShouldAddWriterToWorkflow()
    {
        $writer = $this->getMockWriter();
        $this->workflow->addWriter($writer);

        $this->assertContains($writer, $this->workflow->getWriters());
    }

    /**
     * @test
     * @covers Cocur\Plum\Workflow::addElement()
     * @covers Cocur\Plum\Workflow::addWriter()
     * @covers Cocur\Plum\Workflow::getWriters()
     */
    public function addWriterWithPrependShouldPrependWriterToWorkflow()
    {
        $writer1 = $this->getMockWriter();
        $writer2 = $this->getMockWriter();
        $this->workflow->addWriter($writer1);
        $this->workflow->addWriter($writer2, Workflow::PREPEND);

        $this->assertSame($writer2, $this->workflow->getWriters()[0]);
        $this->assertSame($writer1, $this->workflow->getWriters()[1]);
    }

    /**
     * @test
     * @covers Cocur\Plum\Workflow::process()
     */
    public function processShouldDoNothingWhenNothingIsRead()
    {
        $iterator = m::mock('\Iterator');
        $iterator->shouldReceive('rewind');
        $iterator->shouldReceive('valid')->andReturn(false);

        $reader = $this->getMockReader();
        $reader->shouldReceive('getIterator')->andReturn($iterator);

        $result = $this->workflow->process($reader);

        $this->assertEquals(0, $result->getReadCount());
        $this->assertEquals(0, $result->getWriteCount());
    }

    /**
     * @test
     * @covers Cocur\Plum\Workflow::process()
     * @covers Cocur\Plum\Workflow::processItem()
     */
    public function processShouldApplyFilterToReadItems()
    {
        $iterator = m::mock('\Iterator');
        $iterator->shouldReceive('rewind');
        $iterator->shouldReceive('valid')->andReturn(true)->once();
        $iterator->shouldReceive('current')->andReturn('foobar');
        $iterator->shouldReceive('next');
        $iterator->shouldReceive('valid')->andReturn(false)->once();

        $reader = $this->getMockReader();
        $reader->shouldReceive('getIterator')->andReturn($iterator);

        $filter = $this->getMockFilter();
        $filter->shouldReceive('filter')->with('foobar')->once()->andReturn(false);
        $this->workflow->addFilter($filter);

        $result = $this->workflow->process($reader);

        $this->assertEquals(1, $result->getReadCount());
        $this->assertEquals(0, $result->getWriteCount());
    }

    /**
     * @test
     * @covers Cocur\Plum\Workflow::process()
     * @covers Cocur\Plum\Workflow::processItem()
     * @covers Cocur\Plum\Workflow::convertItem()
     */
    public function processShouldApplyConverterToReadItems()
    {
        $iterator = m::mock('\Iterator');
        $iterator->shouldReceive('rewind');
        $iterator->shouldReceive('valid')->andReturn(true)->once();
        $iterator->shouldReceive('current')->andReturn('foobar');
        $iterator->shouldReceive('next');
        $iterator->shouldReceive('valid')->andReturn(false)->once();

        $reader = $this->getMockReader();
        $reader->shouldReceive('getIterator')->andReturn($iterator);

        $converter = $this->getMockConverter();
        $converter->shouldReceive('convert')->with('foobar')->once()->andReturn('FOOBAR');
        $this->workflow->addConverter($converter);

        $result = $this->workflow->process($reader);

        $this->assertEquals(1, $result->getReadCount());
    }

    /**
     * @test
     * @covers Cocur\Plum\Workflow::process()
     * @covers Cocur\Plum\Workflow::processItem()
     * @covers Cocur\Plum\Workflow::convertItem()
     */
    public function processShouldApplyConverterIfFilterReturnsTrueToReadItems()
    {
        $iterator = m::mock('\Iterator');
        $iterator->shouldReceive('rewind');
        $iterator->shouldReceive('valid')->andReturn(true)->once();
        $iterator->shouldReceive('current')->andReturn('foobar');
        $iterator->shouldReceive('next');
        $iterator->shouldReceive('valid')->andReturn(false)->once();

        $reader = $this->getMockReader();
        $reader->shouldReceive('getIterator')->andReturn($iterator);

        $converter = $this->getMockConverter();
        $converter->shouldReceive('convert')->with('foobar')->once()->andReturn('FOOBAR');

        $filter = $this->getMockFilter();
        $filter->shouldReceive('filter')->with('foobar')->once()->andReturn(true);

        $this->workflow->addConverter($converter, $filter);

        $result = $this->workflow->process($reader);

        $this->assertEquals(1, $result->getReadCount());
    }

    /**
     * @test
     * @covers Cocur\Plum\Workflow::process()
     * @covers Cocur\Plum\Workflow::processItem()
     * @covers Cocur\Plum\Workflow::convertItem()
     */
    public function processShouldNotApplyConverterIfFilterReturnsFalseToReadItems()
    {
        $iterator = m::mock('\Iterator');
        $iterator->shouldReceive('rewind');
        $iterator->shouldReceive('valid')->andReturn(true)->once();
        $iterator->shouldReceive('current')->andReturn('foobar');
        $iterator->shouldReceive('next');
        $iterator->shouldReceive('valid')->andReturn(false)->once();

        $reader = $this->getMockReader();
        $reader->shouldReceive('getIterator')->andReturn($iterator);

        $converter = $this->getMockConverter();
        $converter->shouldReceive('convert')->never();

        $filter = $this->getMockFilter();
        $filter->shouldReceive('filter')->with('foobar')->once()->andReturn(false);

        $this->workflow->addConverter($converter, $filter);

        $result = $this->workflow->process($reader);

        $this->assertEquals(1, $result->getReadCount());
    }

    /**
     * @test
     * @covers Cocur\Plum\Workflow::process()
     * @covers Cocur\Plum\Workflow::processItem()
     * @covers Cocur\Plum\Workflow::prepareWriters()
     * @covers Cocur\Plum\Workflow::finishWriters()
     */
    public function processShouldApplyWriterToReadItems()
    {
        $iterator = m::mock('\Iterator');
        $iterator->shouldReceive('rewind');
        $iterator->shouldReceive('valid')->andReturn(true)->once();
        $iterator->shouldReceive('current')->andReturn('foobar');
        $iterator->shouldReceive('next');
        $iterator->shouldReceive('valid')->andReturn(false)->once();

        $reader = $this->getMockReader();
        $reader->shouldReceive('getIterator')->andReturn($iterator);

        $writer = $this->getMockWriter();
        $writer->shouldReceive('prepare')->once();
        $writer->shouldReceive('finish')->once();
        $writer->shouldReceive('writeItem')->with('foobar')->once();
        $this->workflow->addWriter($writer);

        $result = $this->workflow->process($reader);

        $this->assertEquals(1, $result->getReadCount());
        $this->assertEquals(1, $result->getWriteCount());
        $this->assertEquals(1, $result->getItemWriteCount());
    }

    /**
     * @test
     * @covers Cocur\Plum\Workflow::process()
     * @covers Cocur\Plum\Workflow::processItem()
     * @covers Cocur\Plum\Workflow::prepareWriters()
     * @covers Cocur\Plum\Workflow::finishWriters()
     */
    public function processShouldApplyMultipleWritersToReadItems()
    {
        $iterator = m::mock('\Iterator');
        $iterator->shouldReceive('rewind');
        $iterator->shouldReceive('valid')->andReturn(true)->once();
        $iterator->shouldReceive('current')->andReturn('foobar');
        $iterator->shouldReceive('next');
        $iterator->shouldReceive('valid')->andReturn(false)->once();

        $reader = $this->getMockReader();
        $reader->shouldReceive('getIterator')->andReturn($iterator);

        $writer1 = $this->getMockWriter();
        $writer1->shouldReceive('prepare')->once();
        $writer1->shouldReceive('finish')->once();
        $writer1->shouldReceive('writeItem')->with('foobar')->once();
        $this->workflow->addWriter($writer1);

        $writer2 = $this->getMockWriter();
        $writer2->shouldReceive('prepare')->once();
        $writer2->shouldReceive('finish')->once();
        $writer2->shouldReceive('writeItem')->with('foobar')->once();
        $this->workflow->addWriter($writer2);

        $result = $this->workflow->process($reader);

        $this->assertEquals(1, $result->getReadCount());
        $this->assertEquals(2, $result->getWriteCount());
        $this->assertEquals(1, $result->getItemWriteCount());
    }

    /**
     * @test
     * @covers Cocur\Plum\Workflow::process()
     * @covers Cocur\Plum\Workflow::processItem()
     */
    public function processShouldCollectExceptions()
    {
        $iterator = m::mock('\Iterator');
        $iterator->shouldReceive('rewind');
        $iterator->shouldReceive('valid')->andReturn(true)->once();
        $iterator->shouldReceive('current')->andReturn('foobar');
        $iterator->shouldReceive('next');
        $iterator->shouldReceive('valid')->andReturn(false)->once();

        $reader = $this->getMockReader();
        $reader->shouldReceive('getIterator')->andReturn($iterator);

        $exception = new \Exception();

        $converter = $this->getMockConverter();
        $converter->shouldReceive('convert')->with('foobar')->once()->andThrow($exception);
        $this->workflow->addConverter($converter);

        $result = $this->workflow->process($reader);

        $this->assertEquals(1, $result->getReadCount());
        $this->assertEquals(1, $result->getErrorCount());
        $this->assertContains($exception, $result->getExceptions());
    }

    /**
     * @return \Cocur\Plum\Reader\ReaderInterface|\Mockery\MockInterface
     */
    protected function getMockReader()
    {
        return m::mock('Cocur\Plum\Reader\ReaderInterface');
    }

    /**
     * @return \Cocur\Plum\Writer\WriterInterface|\Mockery\MockInterface
     */
    protected function getMockWriter()
    {
        return m::mock('Cocur\Plum\Writer\WriterInterface');
    }

    /**
     * @return \Cocur\Plum\Converter\ConverterInterface|\Mockery\MockInterface
     */
    protected function getMockConverter()
    {
        return m::mock('Cocur\Plum\Converter\ConverterInterface');
    }

    /**
     * @return \Cocur\Plum\Filter\FilterInterface|\Mockery\MockInterface
     */
    protected function getMockFilter()
    {
        return m::mock('Cocur\Plum\Filter\FilterInterface');
    }
}
