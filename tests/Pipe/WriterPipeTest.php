<?php

namespace Plum\Plum\Pipe;

use Mockery;
use PHPUnit_Framework_TestCase;

/**
 * WriterPipeTest.
 *
 * @author    Florian Eckerstorfer
 * @copyright 2015 Florian Eckerstorfer
 * @group     unit
 */
class WriterPipeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers Plum\Plum\Pipe\WriterPipe::createWriter()
     * @covers Plum\Plum\Pipe\AbstractPipe::getWriter()
     */
    public function createWriterTakesWriterInterface()
    {
        /** @var \Plum\Plum\Writer\WriterInterface $writer */
        $writer = Mockery::mock('\Plum\Plum\Writer\WriterInterface');
        $pipe   = WriterPipe::createWriter($writer);

        $this->assertInstanceOf('\Plum\Plum\Pipe\WriterPipe', $pipe);
        $this->assertEquals($writer, $pipe->getWriter());
    }

    /**
     * @test
     * @covers Plum\Plum\Pipe\WriterPipe::createWriter()
     * @covers Plum\Plum\Pipe\AbstractPipe::getWriter()
     */
    public function createWriterTakesWriterInterfaceInArray()
    {
        /** @var \Plum\Plum\Writer\WriterInterface $writer */
        $writer = Mockery::mock('\Plum\Plum\Writer\WriterInterface');
        $pipe   = WriterPipe::createWriter(['writer' => $writer]);

        $this->assertInstanceOf('\Plum\Plum\Pipe\WriterPipe', $pipe);
        $this->assertEquals($writer, $pipe->getWriter());
    }

    /**
     * @test
     * @covers Plum\Plum\Pipe\WriterPipe::createWriter()
     * @expectedException \InvalidArgumentException
     */
    public function createWriterThrowsExceptionIfNoWriterIsGiven()
    {
        WriterPipe::createWriter('invalid');
    }

    /**
     * @test
     * @covers Plum\Plum\Pipe\WriterPipe::createWriter()
     * @expectedException \InvalidArgumentException
     */
    public function createWriterThrowsExceptionIfNoWriterIsGivenInArray()
    {
        WriterPipe::createWriter(['writer' => 'invalid']);
    }

    /**
     * @test
     * @covers Plum\Plum\Pipe\WriterPipe::createWriter()
     * @covers Plum\Plum\Pipe\WriterPipe::__construct()
     * @covers Plum\Plum\Pipe\WriterPipe::getFilter()
     */
    public function createWriterTakesFilter()
    {
        /** @var \Plum\Plum\Writer\WriterInterface $writer */
        $writer = Mockery::mock('\Plum\Plum\Writer\WriterInterface');
        $filter = Mockery::mock('\Plum\Plum\Filter\FilterInterface');
        $pipe   = WriterPipe::createWriter(['writer' => $writer, 'filter' => $filter]);

        $this->assertInstanceOf('\Plum\Plum\Pipe\WriterPipe', $pipe);
        $this->assertEquals($filter, $pipe->getFilter());
    }

    /**
     * @test
     * @covers Plum\Plum\Pipe\WriterPipe::createWriter()
     * @covers Plum\Plum\Pipe\WriterPipe::__construct()
     * @covers Plum\Plum\Pipe\WriterPipe::getFilter()
     */
    public function createWriterTakesCallbackFilter()
    {
        /** @var \Plum\Plum\Writer\WriterInterface $writer */
        $writer = Mockery::mock('\Plum\Plum\Writer\WriterInterface');
        $filter = function ($v) { return true; };
        $pipe   = WriterPipe::createWriter(['writer' => $writer, 'filter' => $filter]);

        $this->assertInstanceOf('\Plum\Plum\Pipe\WriterPipe', $pipe);
        $this->assertInstanceOf('\Plum\Plum\Filter\FilterInterface', $pipe->getFilter());
    }
}
