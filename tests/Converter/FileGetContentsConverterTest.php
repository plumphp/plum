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
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

/**
 * FileGetContentsConverterTest
 *
 * @package   FlorianEc\Plum\Converter
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 * @group     unit
 */
class FileGetContentsConverterTest extends \PHPUnit_Framework_TestCase
{
    /** @var FileGetContentsConverter */
    private $converter;

    /** @var vfsStreamDirectory */
    private $root;

    public function setUp()
    {
        $structure = ['foo.txt' => 'Hello World'];
        $this->root = vfsStream::setup('fixtures', null, $structure);

        $this->converter = new FileGetContentsConverter();
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Converter\FileGetContentsConverter::convert()
     */
    public function convertShouldGetContentsFromFile()
    {
        $file = new \SplFileInfo(vfsStream::url('fixtures/foo.txt'));
        $item = $this->converter->convert($file);

        $this->assertEquals($file, $item['file']);
        $this->assertEquals('Hello World', $item['content']);
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Converter\FileGetContentsConverter::convert()
     */
    public function convertShouldConvertFilenameIntoSplFileInfo()
    {
        $item = $this->converter->convert(vfsStream::url('fixtures/foo.txt'));

        $this->assertEquals('foo.txt', $item['file']->getFilename());
        $this->assertEquals('Hello World', $item['content']);
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Converter\FileGetContentsConverter::convert()
     */
    public function convertShouldThrowExceptionIfFileDoesNotExists()
    {
        try {
            $file = new \SplFileInfo(__DIR__.'/invalid');
            $this->converter->convert($file);
            $this->assertTrue(false);
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(true);
            $this->assertStringEndsWith('is not readable.', $e->getMessage());
        }
    }
}
