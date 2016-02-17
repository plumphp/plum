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
 * MappingConverter.
 *
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014-2016 Florian Eckerstorfer
 */
class MappingConverter implements ConverterInterface
{
    /**
     * @var array
     */
    protected $mappings;

    /**
     * @param array $mappings
     *
     * @codeCoverageIgnore
     */
    public function __construct(array $mappings = [])
    {
        foreach ($mappings as $mapping) {
            $this->addMapping(
                $mapping['from'],
                $mapping['to'],
                isset($mapping['remove']) ? $mapping['remove'] : true
            );
        }
    }

    /**
     * @param string|array $from
     * @param string|array $to
     * @param bool         $remove
     *
     * @return MappingConverter
     */
    public function addMapping($from, $to, $remove = true)
    {
        $this->mappings[] = ['from' => $from, 'to' => $to, 'remove' => $remove];

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
            if (!empty($mapping['from']) && !empty($mapping['to'])) {
                $item = Vale::set($item, $mapping['to'], Vale::get($item, $mapping['from']));
                if ($mapping['remove']) {
                    $item = Vale::remove($item, $mapping['from']);
                }
            } elseif (!empty($mapping['to'])) {
                $item = Vale::set([], $mapping['to'], $item);
            } elseif (!empty($mapping['from'])) {
                $item = Vale::get($item, $mapping['from']);
            }
        }

        return $item;
    }
}
