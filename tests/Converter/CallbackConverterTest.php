<?php

/**
 * This file is part of florianeckerstorfer/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FlorianEc\Plum\Converter;

/**
 * CallbackConverterTest
 *
 * @package   FlorianEc\Plum\Converter
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 *
 * @group unit
 */
class CallbackConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers FlorianEc\Plum\Converter\CallbackConverter::__construct()
     * @covers FlorianEc\Plum\Converter\CallbackConverter::convert()
     */
    public function convertShouldCallCallback()
    {
        $converter = new CallbackConverter(function ($item) { return 'baz'; });

        $this->assertEquals('baz', $converter->convert('bar'));
    }
}
