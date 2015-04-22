<?php

/**
 * This file is part of plumphp/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plum\Plum\Converter;

use \Mockery as m;

/**
 * LogConverterTest
 *
 * @package   Plum\Plum\Converter
 * @author    Sebastian Göttschkes <sebastian.goettschkes@googlemail.com>
 * @copyright 2015-2015 Florian Eckerstorfer
 * @group     unit
 */
class LogConverterTest extends \PHPUnit_Framework_TestCase
{
    protected $logger;

    public function setUp()
    {
        $this->logger = m::mock('Psr\Log\LoggerInterface');
    }

    /**
     * @test
     * @covers Plum\Plum\Converter\LogConverter::__construct()
     * @covers Plum\Plum\Converter\LogConverter::convert()
     */
    public function convertWithDefaultOptions()
    {
        $converter = new LogConverter($this->logger);
        $item      = ['a' => 'foo', 'b' => 'bar'];

        $this->logger->shouldReceive('log')->with('debug', 'Converting item', $item);

        $converter->convert($item);
    }

    /**
     * @test
     * @covers Plum\Plum\Converter\LogConverter::__construct()
     * @covers Plum\Plum\Converter\LogConverter::convert()
     */
    public function convertWithOptions()
    {
        $converter = new LogConverter($this->logger, 'notice', 'Log Me');
        $item      = ['a' => 'foo', 'b' => 'bar'];

        $this->logger->shouldReceive('log')->with('notice', 'Log Me', $item);

        $converter->convert($item);
    }
}
