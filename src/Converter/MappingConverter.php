<?php

/**
 * This file is part of plumphp/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plum\Plum\Converter;
use Cocur\Vale\Vale;

/**
 * MappingConverter
 *
 * @package   Plum\Plum\Converter
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014-2015 Florian Eckerstorfer
 */
class MappingConverter implements ConverterInterface
{
    /**
     * @var array
     */
    protected $mappings;

    /**
     * @param string|array $from
     * @param string|array $to
     *
     * @return MappingConverter
     */
    public function addMapping($from, $to)
    {
        $this->mappings[] = ['from' => $from, 'to' => $to];

        return $this;
    }

    /**
     * @param mixed $item
     *
     * @return mixed
     */
    public function convert($item)
    {
        foreach ($this->mappings as $mapping) {
            $item = Vale::set($item, $mapping['to'], Vale::get($item, $mapping['from']));
            $item = Vale::remove($item, $mapping['from']);
        }

        return $item;
    }
}
