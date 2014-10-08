<?php

namespace FlorianEc\Plum\Writer;

/**
 * ArrayWriter
 *
 * @package   FlorianEc\Plum\Writer
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 */
class ArrayWriter implements WriterInterface
{
    /** @var array */
    private $data = [];

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Writes the given item to the array.
     *
     * @param $item
     */
    public function writeItem($item)
    {
        $this->data[] = $item;
    }

    public function prepare()
    {
    }

    public function finish()
    {
    }
}
