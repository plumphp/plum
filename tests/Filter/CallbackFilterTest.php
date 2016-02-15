<?php

/**
 * This file is part of plumphp/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Plum\Plum\Filter;

/**
 * CallbackFilterTest.
 *
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014-2015 Florian Eckerstorfer
 * @group     unit
 */
class CallbackFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers Plum\Plum\Filter\CallbackFilter::__construct()
     * @covers Plum\Plum\Filter\CallbackFilter::filter()
     */
    public function filterShouldCallCallback()
    {
        $filter = new CallbackFilter(function ($item) { return true; });

        $this->assertTrue($filter->filter('foobar'));
    }
}
