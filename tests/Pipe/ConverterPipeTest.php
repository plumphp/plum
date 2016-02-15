<?php

namespace Plum\Plum\Pipe;

use Mockery;
use PHPUnit_Framework_TestCase;
use Plum\Plum\Workflow;

/**
 * ConverterPipeTest.
 *
 * @author    Florian Eckerstorfer
 * @copyright 2014-2016 Florian Eckerstorfer
 * @group     unit
 */
class ConverterPipeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers Plum\Plum\Pipe\ConverterPipe::createConverter()
     * @covers Plum\Plum\Pipe\AbstractPipe::getConverter()
     */
    public function createConverterTakesConverterInterface()
    {
        /** @var \Plum\Plum\Converter\ConverterInterface $converter */
        $converter = Mockery::mock('\Plum\Plum\Converter\ConverterInterface');
        $pipe      = ConverterPipe::createConverter($converter);

        $this->assertInstanceOf('Plum\Plum\Pipe\ConverterPipe', $pipe);
        $this->assertEquals($converter, $pipe->getConverter());
    }

    /**
     * @test
     * @covers Plum\Plum\Pipe\ConverterPipe::createConverter()
     * @covers Plum\Plum\Pipe\AbstractPipe::getConverter()
     */
    public function createConverterTakesCallback()
    {
        $callback = function ($item) { return $item; };
        $pipe = ConverterPipe::createConverter($callback);

        $this->assertInstanceOf('Plum\Plum\Pipe\ConverterPipe', $pipe);
        $this->assertInstanceOf('Plum\Plum\Converter\CallbackConverter', $pipe->getConverter());
    }

    /**
     * @test
     * @covers Plum\Plum\Pipe\ConverterPipe::createConverter()
     * @covers Plum\Plum\Pipe\ConverterPipe::hasElementConverter()
     * @covers Plum\Plum\Pipe\AbstractPipe::getConverter()
     */
    public function createConverterTakesConverterInterfaceInArray()
    {
        $converter = Mockery::mock('\Plum\Plum\Converter\ConverterInterface');
        $pipe      = ConverterPipe::createConverter(['converter' => $converter]);

        $this->assertInstanceOf('Plum\Plum\Pipe\ConverterPipe', $pipe);
        $this->assertEquals($converter, $pipe->getConverter());
    }

    /**
     * @test
     * @covers Plum\Plum\Pipe\ConverterPipe::createConverter()
     * @covers Plum\Plum\Pipe\ConverterPipe::hasElementCallbackConverter()
     * @covers Plum\Plum\Pipe\AbstractPipe::getConverter()
     */
    public function createConverterTakesCallbackInArray()
    {
        $callback = function ($item) { return $item; };
        $pipe = ConverterPipe::createConverter(['converter' => $callback]);

        $this->assertInstanceOf('Plum\Plum\Pipe\ConverterPipe', $pipe);
        $this->assertInstanceOf('Plum\Plum\Converter\CallbackConverter', $pipe->getConverter());
    }

    /**
     * @test
     * @covers Plum\Plum\Pipe\ConverterPipe::createConverter()
     * @expectedException \InvalidArgumentException
     */
    public function createConverterThrowsExceptionIfNoConverterIsGiven()
    {
        ConverterPipe::createConverter('invalid');
    }

    /**
     * @test
     * @covers Plum\Plum\Pipe\ConverterPipe::createConverter()
     * @covers Plum\Plum\Pipe\AbstractPipe::__construct()
     * @covers Plum\Plum\Pipe\AbstractPipe::getConverter()
     * @covers Plum\Plum\Pipe\AbstractPipe::setField()
     * @covers Plum\Plum\Pipe\AbstractPipe::getField()
     */
    public function createConverterCreatesValueConverterIfFieldIsPresent()
    {
        $converter = Mockery::mock('\Plum\Plum\Converter\ConverterInterface');
        $pipe      = ConverterPipe::createConverter(['converter' => $converter, 'field' => 'foo']);

        $this->assertInstanceOf('Plum\Plum\Pipe\ConverterPipe', $pipe);
        $this->assertEquals($converter, $pipe->getConverter());
        $this->assertEquals('foo', $pipe->getField());
    }

    /**
     * @test
     * @covers Plum\Plum\Pipe\ConverterPipe::createConverter()
     * @expectedException \InvalidArgumentException
     */
    public function createConverterThrowsExceptionIfNoConverterIsGivenInArray()
    {
        ConverterPipe::createConverter(['converter' => 'invalid']);
    }

    /**
     * @test
     * @covers Plum\Plum\Pipe\ConverterPipe::createConverter()
     * @covers Plum\Plum\Pipe\AbstractPipe::__construct()
     * @covers Plum\Plum\Pipe\AbstractPipe::setFilter()
     * @covers Plum\Plum\Pipe\AbstractPipe::getFilter()
     */
    public function createConverterTakesFilterInArray()
    {
        $converter = Mockery::mock('\Plum\Plum\Converter\ConverterInterface');
        $filter    = Mockery::mock('\Plum\Plum\Filter\FilterInterface');

        $pipe = ConverterPipe::createConverter(['converter' => $converter, 'filter' => $filter]);

        $this->assertInstanceOf('Plum\Plum\Pipe\ConverterPipe', $pipe);
        $this->assertEquals($filter, $pipe->getFilter());
    }

    /**
     * @test
     * @covers Plum\Plum\Pipe\ConverterPipe::createConverter()
     * @covers Plum\Plum\Pipe\AbstractPipe::__construct()
     * @covers Plum\Plum\Pipe\AbstractPipe::setFilter()
     * @covers Plum\Plum\Pipe\AbstractPipe::getFilter()
     */
    public function createConvertersTakesCallbackFilterInArray()
    {
        $converter = Mockery::mock('\Plum\Plum\Converter\ConverterInterface');
        $filter    = function ($v) { return true; };

        $pipe = ConverterPipe::createConverter(['converter' => $converter, 'filter' => $filter]);

        $this->assertInstanceOf('Plum\Plum\Pipe\ConverterPipe', $pipe);
        $this->assertInstanceOf('Plum\Plum\Filter\FilterInterface', $pipe->getFilter());
    }

    /**
     * @test
     * @covers Plum\Plum\Pipe\ConverterPipe::createConverter()
     * @covers Plum\Plum\Pipe\AbstractPipe::__construct()
     * @covers Plum\Plum\Pipe\AbstractPipe::setFilterField()
     * @covers Plum\Plum\Pipe\AbstractPipe::getFilterField()
     */
    public function createConverterTakesFilterFieldInArray()
    {
        $converter = Mockery::mock('\Plum\Plum\Converter\ConverterInterface');

        $pipe = ConverterPipe::createConverter(['converter' => $converter, 'filterField' => 'foo']);

        $this->assertInstanceOf('Plum\Plum\Pipe\ConverterPipe', $pipe);
        $this->assertEquals('foo', $pipe->getFilterField());
    }

    /**
     * @test
     * @covers Plum\Plum\Pipe\ConverterPipe::createConverter()
     * @covers Plum\Plum\Pipe\AbstractPipe::__construct()
     * @covers Plum\Plum\Pipe\AbstractPipe::setPosition()
     * @covers Plum\Plum\Pipe\AbstractPipe::getPosition()
     */
    public function createConverterTakesPositionInArray()
    {
        $converter = Mockery::mock('\Plum\Plum\Converter\ConverterInterface');

        $pipe = ConverterPipe::createConverter(['converter' => $converter, 'position' => Workflow::PREPEND]);

        $this->assertInstanceOf('Plum\Plum\Pipe\ConverterPipe', $pipe);
        $this->assertEquals(Workflow::PREPEND, $pipe->getPosition());
    }
}
