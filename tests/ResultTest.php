<?php

/**
 * This file is part of florianeckerstorfer/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FlorianEc\Plum;

/**
 * ResultTest
 *
 * @package   FlorianEc\Plum
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 * @group     unit
 */
class ResultTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers FlorianEc\Plum\Result::__construct()
     * @covers FlorianEc\Plum\Result::getReadCount()
     */
    public function getReadCountShouldReturnReadCount()
    {
        $result = new Result(42, null);

        $this->assertEquals(42, $result->getReadCount());
    }
    /**
     * @test
     * @covers FlorianEc\Plum\Result::__construct()
     * @covers FlorianEc\Plum\Result::getWriteCount()
     */
    public function getWriteCountShouldReturnWriteCount()
    {
        $result = new Result(null, 42);

        $this->assertEquals(42, $result->getWriteCount());
    }
}
