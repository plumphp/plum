<?php

/**
 * This file is part of florianeckerstorfer/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FlorianEc\Plum\Filter;
use FlorianEc\Plum\Filter\FileExtensionFilter;

/**
 * FileExtensionFilterTest
 *
 * @package   FlorianEc\Plum\Filter
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 * @group     unit
 */
class FileExtensionFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers FlorianEc\Plum\Filter\FileExtensionFilter::__construct()
     * @covers FlorianEc\Plum\Filter\FileExtensionFilter::filter()
     */
    public function convertShouldReturnTrueIfFileExtensionMatches()
    {
        $converter = new FileExtensionFilter('md');

        $this->assertTrue($converter->filter('test.md'));
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Filter\FileExtensionFilter::__construct()
     * @covers FlorianEc\Plum\Filter\FileExtensionFilter::filter()
     */
    public function convertShouldReturnFalseIfFileExtensionNotMatches()
    {
        $converter = new FileExtensionFilter('md');

        $this->assertFalse($converter->filter('test.txt'));
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Filter\FileExtensionFilter::__construct()
     * @covers FlorianEc\Plum\Filter\FileExtensionFilter::filter()
     */
    public function convertShouldReturnTrueIfFileExtensionInArrayMatches()
    {
        $converter = new FileExtensionFilter('md', ['filename']);

        $this->assertTrue($converter->filter(['filename' => 'test.md']));
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Filter\FileExtensionFilter::__construct()
     * @covers FlorianEc\Plum\Filter\FileExtensionFilter::filter()
     */
    public function convertShouldReturnFalseIfFileExtensionInArrayNotMatches()
    {
        $converter = new FileExtensionFilter('md', ['filename']);

        $this->assertFalse($converter->filter(['filename' => 'test.txt']));
    }
}
