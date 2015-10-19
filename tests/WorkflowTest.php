<?php

/**
 * This file is part of plumphp/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plum\Plum;

use \Mockery as m;
use Plum\Plum\Pipe\AbstractPipe;
use Plum\Plum\Reader\ArrayReader;
use Plum\Plum\Writer\ArrayWriter;

/**
 * WorkflowTest
 *
 * @package   Plum\Plum
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014-2015 Florian Eckerstorfer
 * @group     unit
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
     * @covers Plum\Plum\Workflow::getPipeline()
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
     * @covers Plum\Plum\Workflow::getPipeline()
     */
    public function getPipelineShouldReturnOnlyElementsOfGivenType()
    {
        $filter    = $this->getMockFilter();
        $converter = $this->getMockConverter();

        $this->workflow->addFilter($filter);
        $this->workflow->addConverter($converter);

        $this->assertCount(1, $this->workflow->getPipeline(AbstractPipe::TYPE_FILTER));
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::addPipe()
     * @covers Plum\Plum\Workflow::addFilter()
     * @covers Plum\Plum\Workflow::getFilters()
     */
    public function addFilterWithFilterInstanceShouldAddFilterToWorkflow()
    {
        $filter = $this->getMockFilter();
        $this->assertInstanceOf(
            'Plum\Plum\Workflow',
            $this->workflow->addFilter($filter)
        );

        $this->assertSame($filter, $this->workflow->getFilters()[0]->getFilter());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::addPipe()
     * @covers Plum\Plum\Workflow::addFilter()
     * @covers Plum\Plum\Workflow::getFilters()
     */
    public function addFilterWithArrayShouldAddFilterToWorkflow()
    {
        $filter = $this->getMockFilter();
        $this->workflow->addFilter(['filter' => $filter]);

        $this->assertSame($filter, $this->workflow->getFilters()[0]->getFilter());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::addPipe()
     * @covers Plum\Plum\Workflow::addFilter()
     * @covers Plum\Plum\Workflow::getFilters()
     */
    public function addFilterWithPrependShouldPrependFilterToWorkflow()
    {
        $filter1 = $this->getMockFilter();
        $filter2 = $this->getMockFilter();
        $this->workflow->addFilter($filter1);
        $this->workflow->addFilter(['filter' => $filter2, 'position' => Workflow::PREPEND]);

        $this->assertSame($filter2, $this->workflow->getFilters()[0]->getFilter());
        $this->assertSame($filter1, $this->workflow->getFilters()[1]->getFilter());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::addFilter()
     */
    public function addFilterConvertsCallbackIntoCallbackConverter()
    {
        $this->workflow->addFilter(function ($item) { return $item; });

        $this->assertInstanceOf('Plum\Plum\Filter\FilterInterface', $this->workflow->getFilters()[0]->getFilter());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::addFilter()
     */
    public function addFilterConvertsCallbackInArrayIntoCallbackConverter()
    {
        $this->workflow->addFilter(['filter' => function ($item) { return $item; }]);

        $this->assertInstanceOf('Plum\Plum\Filter\FilterInterface', $this->workflow->getFilters()[0]->getFilter());
    }

    /**
     * @test
     * @covers            Plum\Plum\Workflow::addFilter()
     * @expectedException \InvalidArgumentException
     */
    public function addFilterThrowsExceptionIfElementIsNotAFilterAndNotAnArray()
    {
        $this->workflow->addFilter('invalid');
    }

    /**
     * @test
     * @covers            Plum\Plum\Workflow::addFilter()
     * @expectedException \InvalidArgumentException
     */
    public function addFilterThrowsExceptionIfArrayDoesNotContainFilter()
    {
        $this->workflow->addFilter([]);
    }

    /**
     * @test
     * @covers            Plum\Plum\Workflow::addFilter()
     * @expectedException \InvalidArgumentException
     */
    public function addFilterThrowsExceptionIfFilterInArrayIsNotFilter()
    {
        $this->workflow->addFilter(['filter' => 'invalid']);
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::addPipe()
     * @covers Plum\Plum\Workflow::addFilter()
     * @covers Plum\Plum\Workflow::getFilters()
     */
    public function addFilterWithFieldAndFilterInstanceShouldAddValueFilterToWorkflow()
    {
        $filter = $this->getMockFilter();
        $this->assertInstanceOf(
            'Plum\Plum\Workflow',
            $this->workflow->addFilter(['filter' => $filter, 'field' => ['foo']])
        );

        $this->assertSame($filter, $this->workflow->getFilters()[0]->getFilter());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::addPipe()
     * @covers Plum\Plum\Workflow::addFilter()
     * @covers Plum\Plum\Workflow::getFilters()
     */
    public function addFilterWithFieldAndPrependShouldPrependValueFilterToWorkflow()
    {
        $filter1 = $this->getMockFilter();
        $filter2 = $this->getMockFilter();
        $this->workflow->addFilter(['filter' => $filter1, 'field' => ['foo']]);
        $this->workflow->addFilter(['field' => ['foo'], 'filter' => $filter2, 'position' => Workflow::PREPEND]);

        $this->assertSame($filter2, $this->workflow->getFilters()[0]->getFilter());
        $this->assertSame($filter1, $this->workflow->getFilters()[1]->getFilter());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::addFilter()
     */
    public function addFilterWithFieldConvertsCallbackIntoCallbackConverter()
    {
        $this->workflow->addFilter(['filter' => function ($item) { return $item; }, 'field' => ['foo']]);

        $this->assertInstanceOf(
            'Plum\Plum\Filter\FilterInterface',
            $this->workflow->getFilters()[0]->getFilter()
        );
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::addPipe()
     * @covers Plum\Plum\Workflow::addConverter()
     * @covers Plum\Plum\Workflow::getConverters()
     */
    public function addConverterWithConverterInstanceShouldAddConverterToWorkflow()
    {
        $converter = $this->getMockConverter();
        $this->assertInstanceOf(
            'Plum\Plum\Workflow',
            $this->workflow->addConverter($converter)
        );

        $this->assertSame($converter, $this->workflow->getConverters()[0]->getConverter());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::addPipe()
     * @covers Plum\Plum\Workflow::addConverter()
     * @covers Plum\Plum\Workflow::getConverters()
     */
    public function addConverterWithArrayShouldAddConverterToWorkflow()
    {
        $converter = $this->getMockConverter();
        $this->workflow->addConverter(['converter' => $converter]);

        $this->assertSame($converter, $this->workflow->getConverters()[0]->getConverter());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::addPipe()
     * @covers Plum\Plum\Workflow::addConverter()
     * @covers Plum\Plum\Workflow::getConverters()
     */
    public function addConverterWithPrependShouldPrependConverterToWorkflow()
    {
        $converter1 = $this->getMockConverter();
        $converter2 = $this->getMockConverter();
        $this->workflow->addConverter($converter1);
        $this->workflow->addConverter(['converter' => $converter2, 'position' => Workflow::PREPEND]);

        $this->assertSame($converter2, $this->workflow->getConverters()[0]->getConverter());
        $this->assertSame($converter1, $this->workflow->getConverters()[1]->getConverter());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::addConverter()
     */
    public function addConverterConvertsCallbackIntoCallbackConverter()
    {
        $this->workflow->addConverter(function ($item) { return $item; });

        $this->assertInstanceOf(
            'Plum\Plum\Converter\ConverterInterface',
            $this->workflow->getConverters()[0]->getConverter()
        );
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::addConverter()
     */
    public function addConverterConvertsCallbackInArrayIntoCallbackConverter()
    {
        $this->workflow->addConverter(['converter' => function ($item) { return $item; }]);

        $this->assertInstanceOf(
            'Plum\Plum\Converter\ConverterInterface',
            $this->workflow->getConverters()[0]->getConverter()
        );
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::addConverter()
     */
    public function addConverterConvertsFilterCallbackInArrayIntoCallbackFilter()
    {
        $this->workflow->addConverter([
            'converter' => $this->getMockConverter(),
            'filter'    => function ($item) { return true; }
        ]);

        $this->assertInstanceOf(
            'Plum\Plum\Filter\FilterInterface',
            $this->workflow->getConverters()[0]->getFilter()
        );
    }

    /**
     * @test
     * @covers            Plum\Plum\Workflow::addConverter()
     * @expectedException \InvalidArgumentException
     */
    public function addConverterThrowsExceptionIfElementIsNotAConverterAndNotAnArray()
    {
        $this->workflow->addConverter('invalid');
    }

    /**
     * @test
     * @covers            Plum\Plum\Workflow::addConverter()
     * @expectedException \InvalidArgumentException
     */
    public function addConverterThrowsExceptionIfConverterIsMissingInArray()
    {
        $this->workflow->addConverter([]);
    }

    /**
     * @test
     * @covers            Plum\Plum\Workflow::addConverter()
     * @expectedException \InvalidArgumentException
     */
    public function addConverterThrowsExceptionIfConverterInArrayIsNotConverter()
    {
        $this->workflow->addConverter(['converter' => 'invalid']);
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::addPipe()
     * @covers Plum\Plum\Workflow::addConverter()
     * @covers Plum\Plum\Workflow::getConverters()
     */
    public function addConverterWithFieldAndConverterInstanceShouldAddValueConverterToWorkflow()
    {
        $converter = $this->getMockConverter();
        $this->assertInstanceOf(
            'Plum\Plum\Workflow',
            $this->workflow->addConverter(['converter' => $converter, 'field' => ['foo']])
        );

        $this->assertSame($converter, $this->workflow->getConverters()[0]->getConverter());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::addPipe()
     * @covers Plum\Plum\Workflow::addConverter()
     * @covers Plum\Plum\Workflow::getConverters()
     */
    public function addConverterWithFieldAndPrependShouldPrependValueConverterToWorkflow()
    {
        $converter1 = $this->getMockConverter();
        $converter2 = $this->getMockConverter();
        $this->workflow->addConverter(['converter' => $converter1, 'field' => ['foo']]);
        $this->workflow->addConverter([
            'field'     => ['foo'],
            'converter' => $converter2,
            'position'  => Workflow::PREPEND
        ]);

        $this->assertSame($converter2, $this->workflow->getConverters()[0]->getConverter());
        $this->assertSame($converter1, $this->workflow->getConverters()[1]->getConverter());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::addConverter()
     */
    public function addConverterWithFieldAcceptsCallbackAndCreatesCallbackConverter()
    {
        $this->workflow->addConverter(['converter' => function ($item) { return $item; }, 'field' => ['foo']]);

        $this->assertInstanceOf(
            'Plum\Plum\Converter\ConverterInterface',
            $this->workflow->getConverters()[0]->getConverter()
        );
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::addConverter()
     */
    public function addConverterWithFieldAcceptsCallbackFilterInArrayAndCreatesCallbackFilter()
    {
        $this->workflow->addConverter([
            'converter' => $this->getMockConverter(),
            'field'     => ['foo'],
            'filter'    => function ($item) { return true; }
        ]);

        $this->assertInstanceOf(
            'Plum\Plum\Filter\FilterInterface',
            $this->workflow->getConverters()[0]->getFilter()
        );
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::addPipe()
     * @covers Plum\Plum\Workflow::addWriter()
     * @covers Plum\Plum\Workflow::getWriters()
     */
    public function addWriterWithWriterInstanceShouldAddWriterToWorkflow()
    {
        $writer = $this->getMockWriter();
        $this->assertInstanceOf(
            'Plum\Plum\Workflow',
            $this->workflow->addWriter($writer)
        );

        $this->assertSame($writer, $this->workflow->getWriters()[0]->getWriter());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::addPipe()
     * @covers Plum\Plum\Workflow::addWriter()
     * @covers Plum\Plum\Workflow::getWriters()
     */
    public function addWriterWithArrayShouldAddWriterToWorkflow()
    {
        $writer = $this->getMockWriter();
        $this->workflow->addWriter(['writer' => $writer]);

        $this->assertSame($writer, $this->workflow->getWriters()[0]->getWriter());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::addPipe()
     * @covers Plum\Plum\Workflow::addWriter()
     * @covers Plum\Plum\Workflow::getWriters()
     */
    public function addWriterWithPrependShouldPrependWriterToWorkflow()
    {
        $writer1 = $this->getMockWriter();
        $writer2 = $this->getMockWriter();
        $this->workflow->addWriter($writer1);
        $this->workflow->addWriter(['writer' => $writer2, 'position' => Workflow::PREPEND]);

        $this->assertSame($writer2, $this->workflow->getWriters()[0]->getWriter());
        $this->assertSame($writer1, $this->workflow->getWriters()[1]->getWriter());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::addWriter()
     */
    public function addWriterConvertsFilterCallbackInArrayIntoCallbackFilter()
    {
        $this->workflow->addWriter([
            'writer' => $this->getMockWriter(),
            'filter' => function ($item) { return true; }
        ]);

        $this->assertInstanceOf(
            'Plum\Plum\Filter\FilterInterface',
            $this->workflow->getWriters()[0]->getFilter()
        );
    }

    /**
     * @test
     * @covers            Plum\Plum\Workflow::addWriter()
     * @expectedException \InvalidArgumentException
     */
    public function addWriterThrowsExceptionIfElementIsNotAnArrayAndNotAWriter()
    {
        $this->workflow->addWriter('invalid');
    }

    /**
     * @test
     * @covers            Plum\Plum\Workflow::addWriter()
     * @expectedException \InvalidArgumentException
     */
    public function addWriterThrowsExceptionIfWriterIsMissingInArray()
    {
        $this->workflow->addWriter([]);
    }

    /**
     * @test
     * @covers            Plum\Plum\Workflow::addWriter()
     * @expectedException \InvalidArgumentException
     */
    public function addWriterThrowsExceptionIfWriterInArrayIsNotWriter()
    {
        $this->workflow->addWriter(['writer' => 'invalid']);
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::process()
     * @covers Plum\Plum\Workflow::processReader()
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
     * @covers Plum\Plum\Workflow::process()
     * @covers Plum\Plum\Workflow::processReader()
     * @covers Plum\Plum\Workflow::processItem()
     */
    public function processShouldApplyFilterToReadItems()
    {
        $iterator = m::mock('\Iterator');
        $iterator->shouldReceive('rewind');
        $iterator->shouldReceive('valid')->andReturn(true)->twice();
        $iterator->shouldReceive('current')->andReturn('foobar')->once();
        $iterator->shouldReceive('next')->twice();
        $iterator->shouldReceive('current')->andReturn('foo')->once();
        $iterator->shouldReceive('valid')->andReturn(false)->once();

        $reader = $this->getMockReader();
        $reader->shouldReceive('getIterator')->andReturn($iterator);

        $filter = $this->getMockFilter();
        $filter->shouldReceive('filter')->with('foobar')->once()->andReturn(false);
        $filter->shouldReceive('filter')->with('foo')->once()->andReturn(true);
        $this->workflow->addFilter($filter);

        $writer = $this->getMockWriter();
        $writer->shouldReceive('prepare');
        $writer->shouldReceive('finish');
        $writer->shouldReceive('writeItem')->with('foo')->once();
        $this->workflow->addWriter($writer);

        $result = $this->workflow->process($reader);

        $this->assertEquals(2, $result->getReadCount());
        $this->assertEquals(1, $result->getWriteCount());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::process()
     * @covers Plum\Plum\Workflow::processReader()
     * @covers Plum\Plum\Workflow::processItem()
     */
    public function processShouldApplyValueFilterToReadItems()
    {
        $iterator = m::mock('\Iterator');
        $iterator->shouldReceive('rewind');
        $iterator->shouldReceive('valid')->andReturn(true)->twice();
        $iterator->shouldReceive('current')->andReturn(['foo' => 'foobar'])->once();
        $iterator->shouldReceive('current')->andReturn(['foo' => 'foo'])->once();
        $iterator->shouldReceive('next')->twice();
        $iterator->shouldReceive('valid')->andReturn(false)->once();

        $reader = $this->getMockReader();
        $reader->shouldReceive('getIterator')->andReturn($iterator);

        $filter = $this->getMockFilter();
        $filter->shouldReceive('filter')->with('foobar')->once()->andReturn(false);
        $filter->shouldReceive('filter')->with('foo')->once()->andReturn(true);
        $this->workflow->addFilter(['filter' => $filter, 'field' => ['foo']]);

        $writer = $this->getMockWriter();
        $writer->shouldReceive('prepare');
        $writer->shouldReceive('finish');
        $writer->shouldReceive('writeItem')->with(['foo' => 'foo'])->once();
        $this->workflow->addWriter($writer);

        $result = $this->workflow->process($reader);

        $this->assertEquals(2, $result->getReadCount());
        $this->assertEquals(1, $result->getWriteCount());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::process()
     * @covers Plum\Plum\Workflow::processReader()
     * @covers Plum\Plum\Workflow::processItem()
     * @covers Plum\Plum\Workflow::convertItem()
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
     * @covers Plum\Plum\Workflow::process()
     * @covers Plum\Plum\Workflow::processReader()
     * @covers Plum\Plum\Workflow::processItem()
     * @covers Plum\Plum\Workflow::convertItem()
     */
    public function processShouldFilterItemIfConverterReturnsNull()
    {
        $iterator = m::mock('\Iterator');
        $iterator->shouldReceive('rewind');
        $iterator->shouldReceive('valid')->andReturn(true)->twice();
        $iterator->shouldReceive('next')->twice();
        $iterator->shouldReceive('current')->andReturn('foobar')->once();
        $iterator->shouldReceive('current')->andReturn('foo')->once();
        $iterator->shouldReceive('valid')->andReturn(false)->once();

        $reader = $this->getMockReader();
        $reader->shouldReceive('getIterator')->andReturn($iterator);

        $converter = $this->getMockConverter();
        $converter->shouldReceive('convert')->with('foobar')->once()->andReturn(null);
        $converter->shouldReceive('convert')->with('foo')->once()->andReturn('foo');
        $this->workflow->addConverter($converter);

        $writer = $this->getMockWriter();
        $writer->shouldReceive('prepare')->once();
        $writer->shouldReceive('finish')->once();
        $writer->shouldReceive('writeItem')->with('foo')->once();
        $this->workflow->addWriter($writer);

        $result = $this->workflow->process($reader);

        $this->assertEquals(2, $result->getReadCount());
        $this->assertEquals(1, $result->getWriteCount());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::process()
     * @covers Plum\Plum\Workflow::processReader()
     * @covers Plum\Plum\Workflow::processItem()
     * @covers Plum\Plum\Workflow::convertItem()
     */
    public function processShouldApplyConverterIfFilterReturnsTrueToReadItems()
    {
        $iterator = m::mock('\Iterator');
        $iterator->shouldReceive('rewind');
        $iterator->shouldReceive('valid')->andReturn(true)->twice();
        $iterator->shouldReceive('current')->andReturn('foobar')->once();
        $iterator->shouldReceive('current')->andReturn('foo')->once();
        $iterator->shouldReceive('next');
        $iterator->shouldReceive('valid')->andReturn(false)->once();

        $reader = $this->getMockReader();
        $reader->shouldReceive('getIterator')->andReturn($iterator);

        $converter = $this->getMockConverter();
        $converter->shouldReceive('convert')->with('foobar')->once()->andReturn('FOOBAR');
        $converter->shouldReceive('convert')->with('foo')->never();

        $filter = $this->getMockFilter();
        $filter->shouldReceive('filter')->with('foobar')->once()->andReturn(true);
        $filter->shouldReceive('filter')->with('foo')->once()->andReturn(false);

        $this->workflow->addConverter(['converter' => $converter, 'filter' => $filter]);

        $result = $this->workflow->process($reader);

        $this->assertEquals(2, $result->getReadCount());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::process()
     * @covers Plum\Plum\Workflow::processReader()
     * @covers Plum\Plum\Workflow::processItem()
     * @covers Plum\Plum\Workflow::convertItem()
     */
    public function processShouldApplyConverterIfFieldFilterReturnsTrueToReadItems()
    {
        $iterator = m::mock('\Iterator');
        $iterator->shouldReceive('rewind');
        $iterator->shouldReceive('valid')->andReturn(true)->once();
        $iterator->shouldReceive('current')->andReturn(['foo' => 'foobar']);
        $iterator->shouldReceive('next');
        $iterator->shouldReceive('valid')->andReturn(false)->once();

        $reader = $this->getMockReader();
        $reader->shouldReceive('getIterator')->andReturn($iterator);

        $converter = $this->getMockConverter();
        $converter->shouldReceive('convert')->with(['foo' => 'foobar'])->once()->andReturn(['foo' => 'FOOBAR']);

        $filter = $this->getMockFilter();
        $filter->shouldReceive('filter')->with('foobar')->once()->andReturn(true);

        $this->workflow->addConverter(['converter' => $converter, 'filter' => $filter, 'filterField' => 'foo']);

        $result = $this->workflow->process($reader);

        $this->assertEquals(1, $result->getReadCount());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::process()
     * @covers Plum\Plum\Workflow::processReader()
     * @covers Plum\Plum\Workflow::processItem()
     * @covers Plum\Plum\Workflow::convertItem()
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

        $this->workflow->addConverter(['converter' => $converter, 'filter' => $filter]);

        $result = $this->workflow->process($reader);

        $this->assertEquals(1, $result->getReadCount());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::process()
     * @covers Plum\Plum\Workflow::processReader()
     * @covers Plum\Plum\Workflow::processItem()
     * @covers Plum\Plum\Workflow::convertItemValue()
     */
    public function processShouldApplyValueConverterToReadItems()
    {
        $iterator = m::mock('\Iterator');
        $iterator->shouldReceive('rewind');
        $iterator->shouldReceive('valid')->andReturn(true)->once();
        $iterator->shouldReceive('current')->andReturn(['foo' => 'foobar']);
        $iterator->shouldReceive('next');
        $iterator->shouldReceive('valid')->andReturn(false)->once();

        $reader = $this->getMockReader();
        $reader->shouldReceive('getIterator')->andReturn($iterator);

        $converter = $this->getMockConverter();
        $converter->shouldReceive('convert')->with('foobar')->once()->andReturn('FOOBAR');
        $this->workflow->addConverter(['converter' => $converter, 'field' => ['foo']]);

        $writer = $this->getMockWriter();
        $writer->shouldReceive('prepare');
        $writer->shouldReceive('writeItem')->with(['foo' => 'FOOBAR'])->once();
        $writer->shouldReceive('finish');
        $this->workflow->addWriter($writer);

        $result = $this->workflow->process($reader);

        $this->assertEquals(1, $result->getReadCount());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::process()
     * @covers Plum\Plum\Workflow::processReader()
     * @covers Plum\Plum\Workflow::processItem()
     * @covers Plum\Plum\Workflow::convertItemValue()
     */
    public function processShouldApplyValueConverterIfFilterReturnsTrueToReadItems()
    {
        $iterator = m::mock('\Iterator');
        $iterator->shouldReceive('rewind');
        $iterator->shouldReceive('valid')->andReturn(true)->once();
        $iterator->shouldReceive('current')->andReturn(['foo' => 'foobar']);
        $iterator->shouldReceive('next');
        $iterator->shouldReceive('valid')->andReturn(false)->once();

        $reader = $this->getMockReader();
        $reader->shouldReceive('getIterator')->andReturn($iterator);

        $converter = $this->getMockConverter();
        $converter->shouldReceive('convert')->with('foobar')->once()->andReturn('FOOBAR');

        $filter = $this->getMockFilter();
        $filter->shouldReceive('filter')->with(['foo' => 'foobar'])->once()->andReturn(true);

        $this->workflow->addConverter(['field' => ['foo'], 'converter' => $converter, 'filter' => $filter]);

        $writer = $this->getMockWriter();
        $writer->shouldReceive('prepare');
        $writer->shouldReceive('writeItem')->with(['foo' => 'FOOBAR'])->once();
        $writer->shouldReceive('finish');
        $this->workflow->addWriter($writer);

        $result = $this->workflow->process($reader);

        $this->assertEquals(1, $result->getReadCount());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::process()
     * @covers Plum\Plum\Workflow::processReader()
     * @covers Plum\Plum\Workflow::processItem()
     * @covers Plum\Plum\Workflow::convertItemValue()
     */
    public function processShouldApplyValueConverterIfFieldFilterReturnsTrueToReadItems()
    {
        $iterator = m::mock('\Iterator');
        $iterator->shouldReceive('rewind');
        $iterator->shouldReceive('valid')->andReturn(true)->once();
        $iterator->shouldReceive('current')->andReturn(['foo' => 'foobar', 'bar' => 'baz']);
        $iterator->shouldReceive('next');
        $iterator->shouldReceive('valid')->andReturn(false)->once();

        $reader = $this->getMockReader();
        $reader->shouldReceive('getIterator')->andReturn($iterator);

        $converter = $this->getMockConverter();
        $converter->shouldReceive('convert')->with('foobar')->once()->andReturn('FOOBAR');

        $filter = $this->getMockFilter();
        $filter->shouldReceive('filter')->with('baz')->once()->andReturn(true);

        $this->workflow->addConverter([
            'field'       => 'foo',
            'converter'   => $converter,
            'filter'      => $filter,
            'filterField' => 'bar'
        ]);

        $writer = $this->getMockWriter();
        $writer->shouldReceive('prepare');
        $writer->shouldReceive('writeItem')->with(['foo' => 'FOOBAR', 'bar' => 'baz'])->once();
        $writer->shouldReceive('finish');
        $this->workflow->addWriter($writer);

        $result = $this->workflow->process($reader);

        $this->assertEquals(1, $result->getReadCount());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::process()
     * @covers Plum\Plum\Workflow::processReader()
     * @covers Plum\Plum\Workflow::processItem()
     * @covers Plum\Plum\Workflow::convertItemValue()
     */
    public function processShouldNotApplyValueConverterIfFilterReturnsFalseToReadItems()
    {
        $iterator = m::mock('\Iterator');
        $iterator->shouldReceive('rewind');
        $iterator->shouldReceive('valid')->andReturn(true)->once();
        $iterator->shouldReceive('current')->andReturn(['foo' => 'foobar']);
        $iterator->shouldReceive('next');
        $iterator->shouldReceive('valid')->andReturn(false)->once();

        $reader = $this->getMockReader();
        $reader->shouldReceive('getIterator')->andReturn($iterator);

        $converter = $this->getMockConverter();
        $converter->shouldReceive('convert')->never();

        $filter = $this->getMockFilter();
        $filter->shouldReceive('filter')->with(['foo' => 'foobar'])->once()->andReturn(false);

        $this->workflow->addConverter(['field' => ['foo'], 'converter' => $converter, 'filter' => $filter]);

        $result = $this->workflow->process($reader);

        $this->assertEquals(1, $result->getReadCount());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::process()
     * @covers Plum\Plum\Workflow::processReader()
     * @covers Plum\Plum\Workflow::processItem()
     * @covers Plum\Plum\Workflow::prepareWriters()
     * @covers Plum\Plum\Workflow::finishWriters()
     * @covers Plum\Plum\Workflow::writeItem()
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
     * @covers Plum\Plum\Workflow::process()
     * @covers Plum\Plum\Workflow::processItem()
     * @covers Plum\Plum\Workflow::prepareWriters()
     * @covers Plum\Plum\Workflow::finishWriters()
     * @covers Plum\Plum\Workflow::writeItem()
     */
    public function processShouldApplyWriterToReadItemsIfFilterReturnsTrue()
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

        $filter = $this->getMockFilter();
        $filter->shouldReceive('filter')->once()->andReturn(true);

        $this->workflow->addWriter(['writer' => $writer, 'filter' => $filter]);

        $result = $this->workflow->process($reader);

        $this->assertEquals(1, $result->getReadCount());
        $this->assertEquals(1, $result->getWriteCount());
        $this->assertEquals(1, $result->getItemWriteCount());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::process()
     * @covers Plum\Plum\Workflow::processItem()
     * @covers Plum\Plum\Workflow::prepareWriters()
     * @covers Plum\Plum\Workflow::finishWriters()
     * @covers Plum\Plum\Workflow::writeItem()
     */
    public function processShouldNotApplyWriterToReadItemsIfFilterReturnsFalse()
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
        $writer->shouldReceive('writeItem')->never();

        $filter = $this->getMockFilter();
        $filter->shouldReceive('filter')->once()->andReturn(false);

        $this->workflow->addWriter(['writer' => $writer, 'filter' => $filter]);

        $result = $this->workflow->process($reader);

        $this->assertEquals(1, $result->getReadCount());
        $this->assertEquals(0, $result->getWriteCount());
        $this->assertEquals(0, $result->getItemWriteCount());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::process()
     * @covers Plum\Plum\Workflow::processItem()
     * @covers Plum\Plum\Workflow::prepareWriters()
     * @covers Plum\Plum\Workflow::finishWriters()
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
     * @covers Plum\Plum\Workflow::process()
     * @covers Plum\Plum\Workflow::processReader()
     * @covers Plum\Plum\Workflow::processItem()
     * @covers Plum\Plum\Workflow::prepareWriters()
     * @covers Plum\Plum\Workflow::finishWriters()
     * @covers Plum\Plum\Workflow::writeItem()
     */
    public function processShouldReadFromMultipleReaders()
    {
        $iterator1 = m::mock('\Iterator');
        $iterator1->shouldReceive('rewind');
        $iterator1->shouldReceive('valid')->andReturn(true)->once();
        $iterator1->shouldReceive('current')->andReturn('foobar');
        $iterator1->shouldReceive('next');
        $iterator1->shouldReceive('valid')->andReturn(false)->once();

        $reader1 = $this->getMockReader();
        $reader1->shouldReceive('getIterator')->andReturn($iterator1);

        $iterator2 = m::mock('\Iterator');
        $iterator2->shouldReceive('rewind');
        $iterator2->shouldReceive('valid')->andReturn(true)->once();
        $iterator2->shouldReceive('current')->andReturn('foobar');
        $iterator2->shouldReceive('next');
        $iterator2->shouldReceive('valid')->andReturn(false)->once();

        $reader2 = $this->getMockReader();
        $reader2->shouldReceive('getIterator')->andReturn($iterator2);

        $writer = $this->getMockWriter();
        $writer->shouldReceive('prepare')->once();
        $writer->shouldReceive('finish')->once();
        $writer->shouldReceive('writeItem')->with('foobar')->twice();
        $this->workflow->addWriter($writer);

        $result = $this->workflow->process([$reader1, $reader2]);

        $this->assertEquals(2, $result->getReadCount());
        $this->assertEquals(2, $result->getWriteCount());
        $this->assertEquals(2, $result->getItemWriteCount());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::process()
     * @covers Plum\Plum\Workflow::processReader()
     * @covers Plum\Plum\Workflow::processItem()
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
     * @return \Plum\Plum\Reader\ReaderInterface|\Mockery\MockInterface
     */
    protected function getMockReader()
    {
        return m::mock('Plum\Plum\Reader\ReaderInterface');
    }

    /**
     * @return \Plum\Plum\Writer\WriterInterface|\Mockery\MockInterface
     */
    protected function getMockWriter()
    {
        return m::mock('Plum\Plum\Writer\WriterInterface');
    }

    /**
     * @return \Plum\Plum\Converter\ConverterInterface|\Mockery\MockInterface
     */
    protected function getMockConverter()
    {
        return m::mock('Plum\Plum\Converter\ConverterInterface');
    }

    /**
     * @return \Plum\Plum\Filter\FilterInterface|\Mockery\MockInterface
     */
    protected function getMockFilter()
    {
        return m::mock('Plum\Plum\Filter\FilterInterface');
    }
}
