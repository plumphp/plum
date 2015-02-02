<?php

/**
 * This file is part of plumphp/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plum\Plum\Reader;

use Braincrafted\Json\Json;

/**
 * JsonReader
 *
 * @package   Plum\Plum\Reader
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 */
class JsonReader implements ReaderInterface
{
    /** @var string */
    private $fileName;

    /** @var array */
    private $data;

    /**
     * @param string $fileName
     */
    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->getJson());
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->getJson());
    }

    /**
     * @return array
     *
     * @throws \Braincrafted\Json\JsonDecodeException
     */
    protected function getJson()
    {
        if ($this->data === null) {
            $this->data = Json::decode(file_get_contents($this->fileName), true);
        }

        return $this->data;
    }
}
