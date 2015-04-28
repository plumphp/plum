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

        $this->assertCount(1, $this->workflow->getPipeline(Workflow::PIPELINE_TYPE_FILTER));
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::insertElement()
     * @covers Plum\Plum\Workflow::addFilter()
     * @covers Plum\Plum\Workflow::getFilters()
     */
    public function addFilterWithFilterInstanceShouldAddFilterToWorkflow()
    {
        $filter = $this->getMockFilter();
        $this->workflow->addFilter($filter);

        $this->assertSame($filter, $this->workflow->getFilters()[0]['filter']);
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::insertElement()
     * @covers Plum\Plum\Workflow::addFilter()
     * @covers Plum\Plum\Workflow::getFilters()
     */
    public function addFilterWithArrayShouldAddFilterToWorkflow()
    {
        $filter = $this->getMockFilter();
        $this->workflow->addFilter(['filter' => $filter]);

        $this->assertSame($filter, $this->workflow->getFilters()[0]['filter']);
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::insertElement()
     * @covers Plum\Plum\Workflow::addFilter()
     * @covers Plum\Plum\Workflow::getFilters()
     */
    public function addFilterWithPrependShouldPrependFilterToWorkflow()
    {
        $filter1 = $this->getMockFilter();
        $filter2 = $this->getMockFilter();
        $this->workflow->addFilter($filter1);
        $this->workflow->addFilter(['filter' => $filter2, 'position' => Workflow::PREPEND]);

        $this->assertSame($filter2, $this->workflow->getFilters()[0]['filter']);
        $this->assertSame($filter1, $this->workflow->getFilters()[1]['filter']);
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::addFilter()
     */
    public function addFilterConvertsCallbackIntoCallbackConverter()
    {
        $this->workflow->addFilter(function ($item) { return $item; });

        $this->assertInstanceOf('Plum\Plum\Filter\FilterInterface', $this->workflow->getFilters()[0]['filter']);
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::addFilter()
     */
    public function addFilterConvertsCallbackInArrayIntoCallbackConverter()
    {
        $this->workflow->addFilter(['filter' => function ($item) { return $item; }]);

        $this->assertInstanceOf('Plum\Plum\Filter\FilterInterface', $this->workflow->getFilters()[0]['filter']);
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
     * @covers Plum\Plum\Workflow::insertElement()
     * @covers Plum\Plum\Workflow::addValueFilter()
     * @covers Plum\Plum\Workflow::getValueFilters()
     */
    public function addValueFilterWithFilterInstanceShouldAddValueFilterToWorkflow()
    {
        $filter = $this->getMockFilter();
        $this->workflow->addValueFilter($filter, ['foo']);

        $this->assertSame($filter, $this->workflow->getValueFilters()[0]['filter']);
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::insertElement()
     * @covers Plum\Plum\Workflow::addValueFilter()
     * @covers Plum\Plum\Workflow::getValueFilters()
     */
    public function addValueFilterWithArrayShouldAddValueFilterToWorkflow()
    {
        $filter = $this->getMockFilter();
        $this->workflow->addValueFilter(['filter' => $filter, 'field' => ['foo']]);

        $this->assertSame($filter, $this->workflow->getValueFilters()[0]['filter']);
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::insertElement()
     * @covers Plum\Plum\Workflow::addValueFilter()
     * @covers Plum\Plum\Workflow::getValueFilters()
     */
    public function addValueFilterWithPrependShouldPrependValueFilterToWorkflow()
    {
        $filter1 = $this->getMockFilter();
        $filter2 = $this->getMockFilter();
        $this->workflow->addValueFilter($filter1, ['foo']);
        $this->workflow->addValueFilter(['field' => ['foo'], 'filter' => $filter2, 'position' => Workflow::PREPEND]);

        $this->assertSame($filter2, $this->workflow->getValueFilters()[0]['filter']);
        $this->assertSame($filter1, $this->workflow->getValueFilters()[1]['filter']);
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::addValueFilter()
     */
    public function addValueFilterConvertsCallbackIntoCallbackConverter()
    {
        $this->workflow->addValueFilter(function ($item) { return $item; }, ['foo']);

        $this->assertInstanceOf(
            'Plum\Plum\Filter\FilterInterface',
            $this->workflow->getValueFilters()[0]['filter']
        );
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::addValueFilter()
     */
    public function addValueFilterConvertsCallbackInArrayIntoCallbackConverter()
    {
        $this->workflow->addValueFilter(['filter' => function ($item) { return $item; }, 'field' => ['foo']]);

        $this->assertInstanceOf(
            'Plum\Plum\Filter\FilterInterface',
            $this->workflow->getValueFilters()[0]['filter']
        );
    }

    /**
     * @test
     * @covers            Plum\Plum\Workflow::addValueFilter()
     * @expectedException \InvalidArgumentException
     */
    public function addValueFilterThrowsExceptionIfElementIsNotAnArrayAndNotAFilter()
    {
        $this->workflow->addValueFilter('invalid', ['foo']);
    }

    /**
     * @test
     * @covers            Plum\Plum\Workflow::addValueFilter()
     * @expectedException \InvalidArgumentException
     */
    public function addValueFilterThrowsExceptionIfFieldIsMissing()
    {
        $this->workflow->addValueFilter($this->getMockFilter(), null);
    }

    /**
     * @test
     * @covers            Plum\Plum\Workflow::addValueFilter()
     * @expectedException \InvalidArgumentException
     */
    public function addValueFilterThrowsExceptionIfFieldIsMissingInArray()
    {
        $this->workflow->addValueFilter(['filter' => $this->getMockFilter()]);
    }

    /**
     * @test
     * @covers            Plum\Plum\Workflow::addValueFilter()
     * @expectedException \InvalidArgumentException
     */
    public function addValueFilterThrowsExceptionIfFilterIsMissingInArray()
    {
        $this->workflow->addValueFilter(['field' => ['foo']]);
    }

    /**
     * @test
     * @covers            Plum\Plum\Workflow::addValueFilter()
     * @expectedException \InvalidArgumentException
     */
    public function addValueFilterThrowsExceptionIfFilterInArrayIsNotFilter()
    {
        $this->workflow->addValueFilter(['field' => ['foo'], 'filter' => 'invalid']);
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::insertElement()
     * @covers Plum\Plum\Workflow::addConverter()
     * @covers Plum\Plum\Workflow::getConverters()
     */
    public function addConverterWithConverterInstanceShouldAddConverterToWorkflow()
    {
        $converter = $this->getMockConverter();
        $this->workflow->addConverter($converter);

        $this->assertSame($converter, $this->workflow->getConverters()[0]['converter']);
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::insertElement()
     * @covers Plum\Plum\Workflow::addConverter()
     * @covers Plum\Plum\Workflow::getConverters()
     */
    public function addConverterWithArrayShouldAddConverterToWorkflow()
    {
        $converter = $this->getMockConverter();
        $this->workflow->addConverter(['converter' => $converter]);

        $this->assertSame($converter, $this->workflow->getConverters()[0]['converter']);
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::insertElement()
     * @covers Plum\Plum\Workflow::addConverter()
     * @covers Plum\Plum\Workflow::getConverters()
     */
    public function addConverterWithPrependShouldPrependConverterToWorkflow()
    {
        $converter1 = $this->getMockConverter();
        $converter2 = $this->getMockConverter();
        $this->workflow->addConverter($converter1);
        $this->workflow->addConverter(['converter' => $converter2, 'position' => Workflow::PREPEND]);

        $this->assertSame($converter2, $this->workflow->getConverters()[0]['converter']);
        $this->assertSame($converter1, $this->workflow->getConverters()[1]['converter']);
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
            $this->workflow->getConverters()[0]['converter']
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
            $this->workflow->getConverters()[0]['converter']
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
            $this->workflow->getConverters()[0]['filter']
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
     * @covers Plum\Plum\Workflow::insertElement()
     * @covers Plum\Plum\Workflow::addValueConverter()
     * @covers Plum\Plum\Workflow::getValueConverters()
     */
    public function addValueConverterWithConverterInstanceShouldAddValueConverterToWorkflow()
    {
        $converter = $this->getMockConverter();
        $this->workflow->addValueConverter($converter, ['foo']);

        $this->assertSame($converter, $this->workflow->getValueConverters()[0]['converter']);
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::insertElement()
     * @covers Plum\Plum\Workflow::addValueConverter()
     * @covers Plum\Plum\Workflow::getValueConverters()
     */
    public function addValueConverterWithArrayShouldAddValueConverterToWorkflow()
    {
        $converter = $this->getMockConverter();
        $this->workflow->addValueConverter(['converter' => $converter, 'field' => ['foo']]);

        $this->assertSame($converter, $this->workflow->getValueConverters()[0]['converter']);
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::insertElement()
     * @covers Plum\Plum\Workflow::addValueConverter()
     * @covers Plum\Plum\Workflow::getValueConverters()
     */
    public function addValueConverterWithPrependShouldPrependValueConverterToWorkflow()
    {
        $converter1 = $this->getMockConverter();
        $converter2 = $this->getMockConverter();
        $this->workflow->addValueConverter($converter1, ['foo']);
        $this->workflow->addValueConverter([
            'field'     => ['foo'],
            'converter' => $converter2,
            'position'  => Workflow::PREPEND
        ]);

        $this->assertSame($converter2, $this->workflow->getValueConverters()[0]['converter']);
        $this->assertSame($converter1, $this->workflow->getValueConverters()[1]['converter']);
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::addValueConverter()
     */
    public function addValueConverterAcceptsCallbackAndCreatesCallbackConverter()
    {
        $this->workflow->addValueConverter(function ($item) { return $item; }, ['foo']);

        $this->assertInstanceOf(
            'Plum\Plum\Converter\ConverterInterface',
            $this->workflow->getValueConverters()[0]['converter']
        );
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::addValueConverter()
     */
    public function addValueConverterAcceptsCallbackInArrayAndCreatesCallbackConverter()
    {
        $this->workflow->addValueConverter(['converter' => function ($item) { return $item; }, 'field' => ['foo']]);

        $this->assertInstanceOf(
            'Plum\Plum\Converter\ConverterInterface',
            $this->workflow->getValueConverters()[0]['converter']
        );
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::addValueConverter()
     */
    public function addValueConverterAcceptsCallbackFilterInArrayAndCreatesCallbackFilter()
    {
        $this->workflow->addValueConverter([
            'converter' => $this->getMockConverter(),
            'field'     => ['foo'],
            'filter'    => function ($item) { return true; }
        ]);

        $this->assertInstanceOf(
            'Plum\Plum\Filter\FilterInterface',
            $this->workflow->getValueConverters()[0]['filter']
        );
    }

    /**
     * @test
     * @covers            Plum\Plum\Workflow::addValueConverter()
     * @expectedException \InvalidArgumentException
     */
    public function addValueConverterThrowsExceptionIfElementIsNotAnArrayAndNotAConverter()
    {
        $this->workflow->addValueConverter('invalid', ['foo']);
    }

    /**
     * @test
     * @covers            Plum\Plum\Workflow::addValueConverter()
     * @expectedException \InvalidArgumentException
     */
    public function addValueConverterThrowsExceptionIfFieldIsMissing()
    {
        $this->workflow->addValueConverter($this->getMockConverter(), null);
    }

    /**
     * @test
     * @covers            Plum\Plum\Workflow::addValueConverter()
     * @expectedException \InvalidArgumentException
     */
    public function addValueConverterThrowsExceptionIfFieldIsMissingInArray()
    {
        $this->workflow->addValueConverter(['converter' => $this->getMockConverter()]);
    }

    /**
     * @test
     * @covers            Plum\Plum\Workflow::addValueConverter()
     * @expectedException \InvalidArgumentException
     */
    public function addValueConverterThrowsExceptionIfConverterIsMissingInArray()
    {
        $this->workflow->addValueConverter(['field' => ['foo']]);
    }

    /**
     * @test
     * @covers            Plum\Plum\Workflow::addValueConverter()
     * @expectedException \InvalidArgumentException
     */
    public function addValueConverterThrowsExceptionIfConverterIsInArrayIsNotConverter()
    {
        $this->workflow->addValueConverter(['field' => ['foo'], 'converter' => 'invalid']);
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::insertElement()
     * @covers Plum\Plum\Workflow::addWriter()
     * @covers Plum\Plum\Workflow::getWriters()
     */
    public function addWriterWithWriterInstanceShouldAddWriterToWorkflow()
    {
        $writer = $this->getMockWriter();
        $this->workflow->addWriter($writer);

        $this->assertSame($writer, $this->workflow->getWriters()[0]['writer']);
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::insertElement()
     * @covers Plum\Plum\Workflow::addWriter()
     * @covers Plum\Plum\Workflow::getWriters()
     */
    public function addWriterWithArrayShouldAddWriterToWorkflow()
    {
        $writer = $this->getMockWriter();
        $this->workflow->addWriter(['writer' => $writer]);

        $this->assertSame($writer, $this->workflow->getWriters()[0]['writer']);
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::insertElement()
     * @covers Plum\Plum\Workflow::addWriter()
     * @covers Plum\Plum\Workflow::getWriters()
     */
    public function addWriterWithPrependShouldPrependWriterToWorkflow()
    {
        $writer1 = $this->getMockWriter();
        $writer2 = $this->getMockWriter();
        $this->workflow->addWriter($writer1);
        $this->workflow->addWriter(['writer' => $writer2, 'position' => Workflow::PREPEND]);

        $this->assertSame($writer2, $this->workflow->getWriters()[0]['writer']);
        $this->assertSame($writer1, $this->workflow->getWriters()[1]['writer']);
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
            $this->workflow->getWriters()[0]['filter']
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
     * @covers Plum\Plum\Workflow::processItem()
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
     * @covers Plum\Plum\Workflow::process()
     * @covers Plum\Plum\Workflow::processItem()
     */
    public function processShouldApplyValueFilterToReadItems()
    {
        $iterator = m::mock('\Iterator');
        $iterator->shouldReceive('rewind');
        $iterator->shouldReceive('valid')->andReturn(true)->once();
        $iterator->shouldReceive('current')->andReturn(['foo' => 'foobar']);
        $iterator->shouldReceive('next');
        $iterator->shouldReceive('valid')->andReturn(false)->once();

        $reader = $this->getMockReader();
        $reader->shouldReceive('getIterator')->andReturn($iterator);

        $filter = $this->getMockFilter();
        $filter->shouldReceive('filter')->with('foobar')->once()->andReturn(false);
        $this->workflow->addValueFilter($filter, ['foo']);

        $result = $this->workflow->process($reader);

        $this->assertEquals(1, $result->getReadCount());
        $this->assertEquals(0, $result->getWriteCount());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::process()
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
     * @covers Plum\Plum\Workflow::processItem()
     * @covers Plum\Plum\Workflow::convertItem()
     */
    public function processShouldFilterItemIfConverterReturnsNull()
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
        $converter->shouldReceive('convert')->with('foobar')->once()->andReturn(null);
        $this->workflow->addConverter($converter);

        $writer = $this->getMockWriter();
        $writer->shouldReceive('prepare')->once();
        $writer->shouldReceive('finish')->once();
        $writer->shouldReceive('writeItem')->never();
        $this->workflow->addWriter($writer);

        $result = $this->workflow->process($reader);

        $this->assertEquals(1, $result->getReadCount());
        $this->assertEquals(0, $result->getWriteCount());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::process()
     * @covers Plum\Plum\Workflow::processItem()
     * @covers Plum\Plum\Workflow::convertItem()
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

        $this->workflow->addConverter(['converter' => $converter, 'filter' => $filter]);

        $result = $this->workflow->process($reader);

        $this->assertEquals(1, $result->getReadCount());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::process()
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
        $this->workflow->addValueConverter($converter, ['foo']);

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

        $this->workflow->addValueConverter(['field' => ['foo'], 'converter' => $converter, 'filter' => $filter]);

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

        $this->workflow->addValueConverter([
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

        $this->workflow->addValueConverter(['field' => ['foo'], 'converter' => $converter, 'filter' => $filter]);

        $result = $this->workflow->process($reader);

        $this->assertEquals(1, $result->getReadCount());
    }

    /**
     * @test
     * @covers Plum\Plum\Workflow::process()
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
