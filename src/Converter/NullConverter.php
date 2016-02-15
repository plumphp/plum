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
class NullConverter implements ConverterInterface
{
    /**
     * @var mixed
     */
    protected $nullValue;

    /**
     * @param mixed $nullValue
     */
    public function __construct($nullValue = '')
    {
        $this->nullValue = $nullValue;
    }

    /**
     * @param mixed $item
     *
     * @return mixed
     */
    public function convert($item)
    {
        if ($item === null) {
            $item = $this->nullValue;
        }

        return $item;
    }
}
