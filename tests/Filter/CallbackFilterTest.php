<?php

/**
 * This file is part of cocur/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocur\Plum\Filter;

/**
 * CallbackFilterTest
 *
 * @package   Cocur\Plum\Filter
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 *
 * @group unit
 */
class CallbackFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers Cocur\Plum\Filter\CallbackFilter::__construct()
     * @covers Cocur\Plum\Filter\CallbackFilter::filter()
     */
    public function filterShouldCallCallback()
    {
        $filter = new CallbackFilter(function ($item) { return true; });

        $this->assertTrue($filter->filter('foobar'));
    }
}
