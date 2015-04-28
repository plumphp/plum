<?php


namespace Converter;

use PHPUnit_Framework_TestCase;
use Plum\Plum\Converter\MappingConverter;

/**
 * MappingConverterTest
 *
 * @package   Converter
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014-2015 Florian Eckerstorfer
 * @group     unit
 */
class MappingConverterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var MappingConverter
     */
    private $converter;

    public function setUp()
    {
        $this->converter = new MappingConverter();
    }

    /**
     * @test
     * @covers Plum\Plum\Converter\MappingConverter::addMapping()
     * @covers Plum\Plum\Converter\MappingConverter::convert()
     */
    public function convertMapsOneFieldToAnother()
    {
        $this->converter->addMapping(['foo'], ['bar']);
        $result = $this->converter->convert(['foo' => 'foobar']);

        $this->assertCount(1, $result);
        $this->assertSame('foobar', $result['bar']);
    }

    /**
     * @test
     * @covers Plum\Plum\Converter\MappingConverter::addMapping()
     * @covers Plum\Plum\Converter\MappingConverter::convert()
     */
    public function convertWithRemoveOptionDoesNotRemoveSource()
    {
        $this->converter->addMapping(['foo'], ['bar'], false);
        $result = $this->converter->convert(['foo' => 'foobar']);

        $this->assertCount(2, $result);
        $this->assertSame('foobar', $result['foo']);
        $this->assertSame('foobar', $result['bar']);
    }

    /**
     * @test
     * @covers Plum\Plum\Converter\MappingConverter::addMapping()
     * @covers Plum\Plum\Converter\MappingConverter::convert()
     */
    public function convertMapsBasedOnOrder()
    {
        $this->converter->addMapping(['foo'], ['bar']);
        $this->converter->addMapping(['qoo'], ['bar']);
        $result = $this->converter->convert(['foo' => 'foobar', 'qoo' => 'qoobar']);

        // If two mappings point to the same target, the second mapping will overwrite the first one.
        // The first one will be lost
        $this->assertCount(1, $result);
        $this->assertSame('qoobar', $result['bar']);
    }

    /**
     * @test
     * @covers Plum\Plum\Converter\MappingConverter::convert()
     */
    public function convertSetsItemInEmptyArrayIfFromIsEmpty()
    {
        $this->converter->addMapping('', ['bar']);
        $result = $this->converter->convert('foobar');

        $this->assertCount(1, $result);
        $this->assertSame('foobar', $result['bar']);
    }

    /**
     * @test
     * @covers Plum\Plum\Converter\MappingConverter::convert()
     */
    public function convertReturnsFromElementIfToIsEmpty()
    {
        $this->converter->addMapping(['foo'], '');
        $result = $this->converter->convert(['foo' => 'foobar']);

        $this->assertSame('foobar', $result);
    }

    /**
     * @test
     * @covers Plum\Plum\Converter\MappingConverter::convert()
     */
    public function convertReturnsItemIfFromAndToAreEmpty()
    {
        $this->converter->addMapping('', '');

        $this->assertSame('foobar', $this->converter->convert('foobar'));
    }
}
