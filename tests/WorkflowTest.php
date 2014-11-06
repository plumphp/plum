<?php

/**
 * This file is part of florianeckerstorfer/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
        $filter    = $this->getMockFilter();
        $converter = $this->getMockConverter();

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
        $filter    = $this->getMockFilter();
        $converter = $this->getMockConverter();

        $this->workflow->addFilter($filter);
        $this->workflow->addConverter($converter);

        $this->assertCount(1, $this->workflow->getPipeline(Workflow::PIPELINE_TYPE_FILTER));
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Workflow::addFilter()
     * @covers FlorianEc\Plum\Workflow::getFilters()
     */
    public function addFilterShouldAddFilterToWorkflow()
    {
        $filter = $this->getMockFilter();
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
        $converter = $this->getMockConverter();
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
        $writer = $this->getMockWriter();
        $this->workflow->addWriter($writer);

        $this->assertContains($writer, $this->workflow->getWriters());
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Workflow::process()
     */
    public function processShouldDoNothingWhenNothingIsRead()
    {
        $reader = $this->getMockReader();
        $reader->shouldReceive('rewind');
        $reader->shouldReceive('valid')->andReturn(false);

        $result = $this->workflow->process($reader);

        $this->assertEquals(0, $result->getReadCount());
        $this->assertEquals(0, $result->getWriteCount());
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Workflow::process()
     * @covers FlorianEc\Plum\Workflow::processItem()
     */
    public function processShouldApplyFilterToReadItems()
    {
        $reader = $this->getMockReader();
        $reader->shouldReceive('rewind');
        $reader->shouldReceive('valid')->andReturn(true)->once();
        $reader->shouldReceive('current')->andReturn('foobar');
        $reader->shouldReceive('next');
        $reader->shouldReceive('valid')->andReturn(false)->once();

        $filter = $this->getMockFilter();
        $filter->shouldReceive('filter')->with('foobar')->once();
        $this->workflow->addFilter($filter);

        $result = $this->workflow->process($reader);

        $this->assertEquals(1, $result->getReadCount());
        $this->assertEquals(0, $result->getWriteCount());
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Workflow::process()
     * @covers FlorianEc\Plum\Workflow::processItem()
     * @covers FlorianEc\Plum\Workflow::applyConverter()
     */
    public function processShouldApplyConverterToReadItems()
    {
        $reader = $this->getMockReader();
        $reader->shouldReceive('rewind');
        $reader->shouldReceive('valid')->andReturn(true)->once();
        $reader->shouldReceive('current')->andReturn('foobar');
        $reader->shouldReceive('next');
        $reader->shouldReceive('valid')->andReturn(false)->once();

        $converter = $this->getMockConverter();
        $converter->shouldReceive('convert')->with('foobar')->once()->andReturn('FOOBAR');
        $this->workflow->addConverter($converter);

        $result = $this->workflow->process($reader);

        $this->assertEquals(1, $result->getReadCount());
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Workflow::process()
     * @covers FlorianEc\Plum\Workflow::processItem()
     * @covers FlorianEc\Plum\Workflow::applyConverter()
     */
    public function processShouldApplyConverterIfFilterReturnsTrueToReadItems()
    {
        $reader = $this->getMockReader();
        $reader->shouldReceive('rewind');
        $reader->shouldReceive('valid')->andReturn(true)->once();
        $reader->shouldReceive('current')->andReturn('foobar');
        $reader->shouldReceive('next');
        $reader->shouldReceive('valid')->andReturn(false)->once();

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
     * @covers FlorianEc\Plum\Workflow::process()
     * @covers FlorianEc\Plum\Workflow::processItem()
     * @covers FlorianEc\Plum\Workflow::applyConverter()
     */
    public function processShouldNotApplyConverterIfFilterReturnsFalseToReadItems()
    {
        $reader = $this->getMockReader();
        $reader->shouldReceive('rewind');
        $reader->shouldReceive('valid')->andReturn(true)->once();
        $reader->shouldReceive('current')->andReturn('foobar');
        $reader->shouldReceive('next');
        $reader->shouldReceive('valid')->andReturn(false)->once();

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
     * @covers FlorianEc\Plum\Workflow::process()
     * @covers FlorianEc\Plum\Workflow::processItem()
     */
    public function processShouldApplyWriterToReadItems()
    {
        $reader = $this->getMockReader();
        $reader->shouldReceive('rewind');
        $reader->shouldReceive('valid')->andReturn(true)->once();
        $reader->shouldReceive('current')->andReturn('foobar');
        $reader->shouldReceive('next');
        $reader->shouldReceive('valid')->andReturn(false)->once();

        $writer = $this->getMockWriter();
        $writer->shouldReceive('prepare')->once();
        $writer->shouldReceive('finish')->once();
        $writer->shouldReceive('writeItem')->with('foobar')->once();
        $this->workflow->addWriter($writer);

        $result = $this->workflow->process($reader);

        $this->assertEquals(1, $result->getReadCount());
        $this->assertEquals(1, $result->getWriteCount());
    }

    /**
     * @return \FlorianEc\Plum\Reader\ReaderInterface|\Mockery\MockInterface
     */
    protected function getMockReader()
    {
        return m::mock('FlorianEc\Plum\Reader\ReaderInterface');
    }

    /**
     * @return \FlorianEc\Plum\Writer\WriterInterface|\Mockery\MockInterface
     */
    protected function getMockWriter()
    {
        return m::mock('FlorianEc\Plum\Writer\WriterInterface');
    }

    /**
     * @return \FlorianEc\Plum\Converter\ConverterInterface|\Mockery\MockInterface
     */
    protected function getMockConverter()
    {
        return m::mock('FlorianEc\Plum\Converter\ConverterInterface');
    }

    /**
     * @return \FlorianEc\Plum\Filter\FilterInterface|\Mockery\MockInterface
     */
    protected function getMockFilter()
    {
        return m::mock('FlorianEc\Plum\Filter\FilterInterface');
    }
}
