<?php

/**
 * This file is part of plumphp/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plum\Plum\Writer;

use \Mockery as m;

/**
 * ConsoleProgressWriterTest
 *
 * @package   Plum\Plum\Writer
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 * @group     unit
 */
class ConsoleProgressWriterTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Symfony\Component\Console\Helper\ProgressBar|\Mockery\MockInterface */
    private $progressBar;

    /** @var ConsoleProgressWriter */
    private $writer;

    public function setUp()
    {
        $this->progressBar = m::mock('Symfony\Component\Console\Helper\ProgressBar');
        $this->writer = new ConsoleProgressWriter($this->progressBar);
    }

    /**
     * @test
     * @covers Plum\Plum\Writer\ConsoleProgressWriter::writeItem()
     */
    public function writeItemShouldCallAdvanceOnProgressBar()
    {
        $this->progressBar->shouldReceive('advance')->once();

        $this->writer->writeItem([]);
    }

    /**
     * @test
     * @covers Plum\Plum\Writer\ConsoleProgressWriter::prepare()
     */
    public function prepareShouldCallStartOnProgressBar()
    {
        $this->progressBar->shouldReceive('start')->once();

        $this->writer->prepare();
    }

    /**
     * @test
     * @covers Plum\Plum\Writer\ConsoleProgressWriter::finish()
     */
    public function finishShouldCallFinishOnProgressBar()
    {
        $this->progressBar->shouldReceive('finish')->once();

        $this->writer->finish();
    }
}
