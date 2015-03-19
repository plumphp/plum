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
 * NullConverterTest
 *
 * @package   Plum\Plum\Converter
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014-2015 Florian Eckerstorfer
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

        $this->assertEquals('', $converter->convert(null));
    }

    /**
     * @test
     * @covers Plum\Plum\Converter\NullConverter::convert()
     */
    public function convertShouldConvertNullToEmptyStringInArray()
    {
        $converter = new NullConverter();

        $this->assertEquals(['a', ''], $converter->convert(['a', null]));
    }
}
