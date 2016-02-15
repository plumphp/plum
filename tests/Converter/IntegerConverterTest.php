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
 * IntegerConverterTest.
 *
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014-2016 Florian Eckerstorfer
 * @group     unit
 */
class IntegerConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers Plum\Plum\Converter\NullConverter::convert()
     */
    public function convertShouldConvertFloatToInteger()
    {
        $converter = new IntegerConverter();

        $this->assertSame(42, $converter->convert(42));
        $this->assertSame(42, $converter->convert(42.69));
    }
    /**
     * @test
     * @covers Plum\Plum\Converter\NullConverter::convert()
     */
    public function convertShouldConvertStringToInteger()
    {
        $converter = new IntegerConverter();

        $this->assertSame(42, $converter->convert('42'));
        $this->assertSame(42, $converter->convert('42 houses'));
    }
}
