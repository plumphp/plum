<?php

namespace FlorianEc\Plum;

use \Mockery as m;

/**
 * WorkflowTest
 *
 * @package   FlorianEc\Plum
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
     * @covers FlorianEc\Plum\Workflow::getPipeline()
     */
    public function getPipelineShouldReturnAllPipelineElements()
    {
        /** @var \FlorianEc\Plum\Filter\FilterInterface $filter */
        $filter = m::mock('FlorianEc\Plum\Filter\FilterInterface');
        /** @var \FlorianEc\Plum\Converter\ConverterInterface $converter */
        $converter = m::mock('FlorianEc\Plum\Converter\ConverterInterface');

        $this->workflow->addFilter($filter);
        $this->workflow->addConverter($converter);

        $this->assertCount(2, $this->workflow->getPipeline());
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Workflow::getPipeline()
     */
    public function getPipelineShouldReturnOnlyElementsOfGivenType()
    {
        /** @var \FlorianEc\Plum\Filter\FilterInterface $filter */
        $filter = m::mock('FlorianEc\Plum\Filter\FilterInterface');
        /** @var \FlorianEc\Plum\Converter\ConverterInterface $converter */
        $converter = m::mock('FlorianEc\Plum\Converter\ConverterInterface');

        $this->workflow->addFilter($filter);
        $this->workflow->addConverter($converter);

        $this->assertCount(1, $this->workflow->getPipeline('FlorianEc\Plum\Filter\FilterInterface'));
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Workflow::addFilter()
     * @covers FlorianEc\Plum\Workflow::getFilters()
     */
    public function addFilterShouldAddFilterToWorkflow()
    {
        /** @var \FlorianEc\Plum\Filter\FilterInterface $filter */
        $filter = m::mock('FlorianEc\Plum\Filter\FilterInterface');
        $this->workflow->addFilter($filter);

        $this->assertContains($filter, $this->workflow->getFilters());
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Workflow::addConverter()
     * @covers FlorianEc\Plum\Workflow::getConverters()
     */
    public function addConverterShouldAddConverterToWorkflow()
    {
        /** @var \FlorianEc\Plum\Converter\ConverterInterface $converter */
        $converter = m::mock('FlorianEc\Plum\Converter\ConverterInterface');
        $this->workflow->addConverter($converter);

        $this->assertContains($converter, $this->workflow->getConverters());
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Workflow::addWriter()
     * @covers FlorianEc\Plum\Workflow::getWriters()
     */
    public function addWriterShouldAddWriterToWorkflow()
    {
        /** @var \FlorianEc\Plum\Writer\WriterInterface $writer */
        $writer = m::mock('FlorianEc\Plum\Writer\WriterInterface');
        $this->workflow->addWriter($writer);

        $this->assertContains($writer, $this->workflow->getWriters());
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Workflow::process()
     */
    public function processShouldDoNothingIsRead()
    {
        /** @var \FlorianEc\Plum\Reader\ReaderInterface|\Mockery\MockInterface $reader */
        $reader = m::mock('FlorianEc\Plum\Reader\ReaderInterface');
        $reader->shouldReceive('rewind');
        $reader->shouldReceive('valid')->andReturn(false);

        $this->workflow->process($reader);
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Workflow::process()
     * @covers FlorianEc\Plum\Workflow::processItem()
     */
    public function processShouldApplyFilterToReadItems()
    {
        /** @var \FlorianEc\Plum\Reader\ReaderInterface|\Mockery\MockInterface $reader */
        $reader = m::mock('FlorianEc\Plum\Reader\ReaderInterface');
        $reader->shouldReceive('rewind');
        $reader->shouldReceive('valid')->andReturn(true)->once();
        $reader->shouldReceive('current')->andReturn('foobar');
        $reader->shouldReceive('next');
        $reader->shouldReceive('valid')->andReturn(false)->once();

        /** @var \FlorianEc\Plum\Filter\FilterInterface|\Mockery\MockInterface $filter */
        $filter = m::mock('FlorianEc\Plum\Filter\FilterInterface');
        $filter->shouldReceive('filter')->with('foobar')->once();
        $this->workflow->addFilter($filter);

        $this->workflow->process($reader);
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Workflow::process()
     * @covers FlorianEc\Plum\Workflow::processItem()
     */
    public function processShouldApplyConverterToReadItems()
    {
        /** @var \FlorianEc\Plum\Reader\ReaderInterface|\Mockery\MockInterface $reader */
        $reader = m::mock('FlorianEc\Plum\Reader\ReaderInterface');
        $reader->shouldReceive('rewind');
        $reader->shouldReceive('valid')->andReturn(true)->once();
        $reader->shouldReceive('current')->andReturn('foobar');
        $reader->shouldReceive('next');
        $reader->shouldReceive('valid')->andReturn(false)->once();

        /** @var \FlorianEc\Plum\Converter\ConverterInterface|\Mockery\MockInterface $converter */
        $converter = m::mock('FlorianEc\Plum\Converter\ConverterInterface');
        $converter->shouldReceive('convert')->with('foobar')->once()->andReturn('FOOBAR');
        $this->workflow->addConverter($converter);

        $this->workflow->process($reader);
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Workflow::process()
     * @covers FlorianEc\Plum\Workflow::processItem()
     */
    public function processShouldApplyWriterToReadItems()
    {
        /** @var \FlorianEc\Plum\Reader\ReaderInterface|\Mockery\MockInterface $reader */
        $reader = m::mock('FlorianEc\Plum\Reader\ReaderInterface');
        $reader->shouldReceive('rewind');
        $reader->shouldReceive('valid')->andReturn(true)->once();
        $reader->shouldReceive('current')->andReturn('foobar');
        $reader->shouldReceive('next');
        $reader->shouldReceive('valid')->andReturn(false)->once();

        /** @var \FlorianEc\Plum\Writer\WriterInterface|\Mockery\MockInterface $writer */
        $writer = m::mock('FlorianEc\Plum\Writer\WriterInterface');
        $writer->shouldReceive('prepare')->once();
        $writer->shouldReceive('finish')->once();
        $writer->shouldReceive('writeItem')->with('foobar')->once();
        $this->workflow->addWriter($writer);

        $this->workflow->process($reader);
    }
}
 