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
 * SkipFirstFilterTest
 *
 * @package   Plum\Plum\Filter
 * @author    Sebastian Göttschkes <sebastian.goettschkes@googlemail.com>
 * @copyright 2015 Florian Eckerstorfer
 *
 * @group unit
 */
class SkipFirstFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers Plum\Plum\Filter\SkipFirstFilter::__construct()
     * @covers Plum\Plum\Filter\SkipFirstFilter::filter()
     */
    public function filterShouldSkipNoneWithCounter0()
    {
        $filter = new SkipFirstFilter(0);

        $this->assertTrue($filter->filter('foobar'));
    }

    /**
     * @test
     * @covers Plum\Plum\Filter\SkipFirstFilter::__construct()
     * @covers Plum\Plum\Filter\SkipFirstFilter::filter()
     */
    public function filterShouldSkipOneWithCounter1()
    {
        $filter = new SkipFirstFilter(1);

        $this->assertFalse($filter->filter('foo'));
        $this->assertTrue($filter->filter('bar'));
    }

    /**
     * @test
     * @expectedException \LogicException
     * @covers Plum\Plum\Filter\SkipFirstFilter::__construct()
     * @covers Plum\Plum\Filter\SkipFirstFilter::filter()
     */
    public function filterThrowExceptionIfConstructedWrong()
    {
        new SkipFirstFilter('wrong');
    }
}
