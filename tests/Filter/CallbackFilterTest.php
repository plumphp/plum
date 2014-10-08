<?php

namespace FlorianEc\Plum\Filter;

/**
 * CallbackFilterTest
 *
 * @package   FlorianEc\Plum\Filter
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 *
 * @group unit
 */
class CallbackFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers FlorianEc\Plum\Filter\CallbackFilter::__construct()
     * @covers FlorianEc\Plum\Filter\CallbackFilter::filter()
     */
    public function filterShouldCallCallback()
    {
        $filter = new CallbackFilter(function ($item) { return true; });

        $this->assertTrue($filter->filter('foobar'));
    }
}
