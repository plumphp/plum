<?php

/**
 * This file is part of plumphp/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plum\Plum\Writer;

/**
 * CsvWriter
 *
 * @package   Plum\Plum\Writer
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

    /** @var string[]|null */
    private $header;

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
     * @param string[] $header
     *
     * @return CsvWriter
     */
    public function setHeader(array $header)
    {
        $this->header = $header;

        return $this;
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
        if (false === is_resource($this->fileHandle)) {
            throw new \LogicException(sprintf(
                'There exists no file handle for the file "%s". Please call prepare() before writing items.',
                $this->filename
            ));
        }
        $item = array_map(function ($v) {
            return $this->enclosure.$v.$this->enclosure;
        }, $item);
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
        if ($this->header !== null) {
            $this->writeItem($this->header);
        }
    }

    /**
     * Finish the writer.
     *
     * @return void
     */
    public function finish()
    {
        if (false === is_resource($this->fileHandle)) {
            throw new \LogicException(sprintf(
                'There exists no file handle for the file "%s". For this instance of Plum\Plum\CsvWriter either'.
                ' prepare() has never been called or finish() has already been called.',
                $this->filename
            ));
        }
        fclose($this->fileHandle);
    }
}
