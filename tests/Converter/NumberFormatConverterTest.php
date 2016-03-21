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

use PHPUnit_Framework_TestCase;

/**
 * NumberFormatConverterTest.
 *
 * @author    Florian Eckerstorfer
 * @copyright 2014-2016 Florian Eckerstorfer
 * @group     unit
 */
class NumberFormatConverterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers Plum\Plum\Converter\NumberFormatConverter::convert()
     */
    public function convertFormatsNumberWithDefaultParmeters()
    {
        $converter = new NumberFormatConverter();

        $this->assertEquals('42', $converter->convert(42.42));
    }
    
    /**
     * @test
     * @covers Plum\Plum\Converter\NumberFormatConverter::convert()
     */
    public function convertFormatsNumber()
    {
        $converter = new NumberFormatConverter(2, ',', '.');

        $this->assertEquals('10.042,11', $converter->convert(10042.112));
    }
}
