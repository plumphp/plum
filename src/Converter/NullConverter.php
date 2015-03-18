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
 * NullConverter
 *
 * @package   Plum\Plum\Converter
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 */
class NullConverter implements ConverterInterface
{
    /**
     * @param mixed $item
     *
     * @return mixed
     */
    public function convert($item)
    {
        if (is_array($item)) {
            foreach ($item as $key => $value) {
                if ($value === null) {
                    $item[$key] = '';
                }
            }
        } else {
            if ($item === null) {
                $item = '';
            }
        }

        return $item;
    }
}
