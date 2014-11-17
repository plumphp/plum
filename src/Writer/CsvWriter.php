<?php

namespace Cocur\Plum\Writer;

/**
 * CsvWriter
 *
 * @package   Cocur\Plum\Writer
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 */
class CsvWriter implements WriterInterface
{
    /** @var resource */
    private $fileHandle;

    /** @var string */
    private $filename;

    /** @var string */
    private $separator;

    /** @var string */
    private $enclosure;

    /**
     * @param string $filename
     * @param string $separator
     * @param string $enclosure
     */
    public function __construct($filename, $separator = ',', $enclosure = '"')
    {
        $this->filename  = $filename;
        $this->separator = $separator;
        $this->enclosure = $enclosure;
    }
    /**
     * Write the given item.
     *
     * @param mixed $item
     *
     * @return void
     */
    public function writeItem($item)
    {
        if (!is_resource($this->fileHandle)) {
            throw new \LogicException(sprintf(
                'There exists no file handle for the file "%s". Please call prepare() before writing items.',
                $this->filename
            ));
        }
        $item = array_map(function ($v) { return $this->enclosure.$v.$this->enclosure; }, $item);
        fwrite($this->fileHandle, implode($this->separator, $item)."\n");
    }

    /**
     * Prepare the writer.
     *
     * @return void
     */
    public function prepare()
    {
        $this->fileHandle = fopen($this->filename, 'w');
    }

    /**
     * Finish the writer.
     *
     * @return void
     */
    public function finish()
    {
        if (!is_resource($this->fileHandle)) {
            throw new \LogicException(sprintf(
                'There exists no file handle for the file "%s". For this instance of Cocur\Plum\CsvWriter either'.
                ' prepare() has never been called or finish() has already been called.',
                $this->filename
            ));
        }
        fclose($this->fileHandle);
    }
}
