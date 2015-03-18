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
 * HeaderConverterTest
 *
 * @package   Plum\Plum\Converter
 * @author    Sebastian Göttschkes <sebastian.goettschkes@googlemail.com>
 * @copyright 2015 Florian Eckerstorfer
 * @group     unit
 */
class HeaderConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers Plum\Plum\Converter\HeaderConverter::convert()
     */
    public function convertFirstLine()
    {
        $headerConverter = new HeaderConverter();
        $header = ['Headline #1', '#2', '3'];

        $this->assertSame($header, $headerConverter->convert($header));

        return $headerConverter;
    }

    /**
     * @test
     * @depends convertFirstLine
     * @covers Plum\Plum\Converter\HeaderConverter::convert()
     */
    public function convertSecondLine($headerConverter)
    {
        $secondLine = $headerConverter->convert(['Data field #1', 'foo', 'bar']);

        $this->assertSame('Data field #1', $secondLine['Headline #1']);
        $this->assertSame('bar', $secondLine['3']);
    }

    /**
     * @test
     * @covers Plum\Plum\Converter\HeaderConverter::convert()
     */
    public function convertConvertColumnIfNoHeaderExists()
    {
        $converter = new HeaderConverter();
        $converter->convert(['a', 'b']);

        // Row has more elements than header row.
        $result = $converter->convert(['1', '2', '3']);

        $this->assertSame('1', $result['a']);
        $this->assertSame('2', $result['b']);
        $this->assertSame('3', $result[2]); // Access using index
    }
}
