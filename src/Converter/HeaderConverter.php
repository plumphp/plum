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
 * HeaderConverter.
 *
 * @author    Sebastian Göttschkes <sebastian.goettschkes@googlemail.com>
 * @copyright 2014-2016 Florian Eckerstorfer
 */
class HeaderConverter implements ConverterInterface
{
    /** @var string[]|null */
    private $header = null;

    /**
     * {@inheritdoc}
     */
    public function convert($item)
    {
        if ($this->header === null) {
            $this->header = $item;

            return $item;
        }

        $newItem = [];
        foreach ($item as $key => $value) {
            if (isset($this->header[$key])) {
                $key = $this->header[$key];
            }
            $newItem[$key] = $value;
        }

        return $newItem;
    }
}
