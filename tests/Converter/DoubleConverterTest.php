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
 * DoubleConverterTest.
 *
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014-2016 Florian Eckerstorfer
 * @group     unit
 */
class DoubleConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers Plum\Plum\Converter\DoubleConverter::convert()
     */
    public function convertShouldConvertIntegerToFloat()
    {
        $converter = new DoubleConverter();

        $this->assertSame(42.00, $converter->convert(42));
    }

    /**
     * @test
     * @covers Plum\Plum\Converter\DoubleConverter::convert()
     */
    public function convertShouldConvertStringToFloat()
    {
        $converter = new DoubleConverter();

        $this->assertSame(42.69, $converter->convert('42.69 value'));
    }
}
