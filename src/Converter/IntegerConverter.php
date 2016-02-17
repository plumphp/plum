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

/**
 * NullConverter.
 *
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014-2016 Florian Eckerstorfer
 */
class IntegerConverter implements ConverterInterface
{
    /**
     * Converts the item into an integer value.
     *
     * @param mixed $item
     *
     * @return int
     */
    public function convert($item)
    {
        return intval($item);
    }
}
