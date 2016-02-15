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

/**
 * NullConverterTest.
 *
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014-2016 Florian Eckerstorfer
 * @group     unit
 */
class NullConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers Plum\Plum\Converter\NullConverter::convert()
     */
    public function convertShouldConvertNullToEmptyString()
    {
        $converter = new NullConverter();

        $this->assertSame('', $converter->convert(null));
        $this->assertSame('foo', $converter->convert('foo'));
    }

    /**
     * @test
     * @covers Plum\Plum\Converter\NullConverter::__construct()
     * @covers Plum\Plum\Converter\NullConverter::convert()
     */
    public function convertShouldConvertNullToDefinedNullValue()
    {
        $converter = new NullConverter(0);

        $this->assertSame(0, $converter->convert(null));
    }
}
