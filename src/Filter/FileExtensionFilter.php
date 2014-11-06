<?php

/**
 * This file is part of florianeckerstorfer/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FlorianEc\Plum\Filter;

use FlorianEc\Plum\Filter\FilterInterface;

/**
 * FileExtensionFilter
 *
 * @package   FlorianEc\Plum\Converter
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 */
class FileExtensionFilter implements FilterInterface
{
    /** @var string */
    private $extension;

    /** @var string[]|null */
    private $property;

    /**
     * @param string        $extension
     * @param string[]|null $property
     */
    public function __construct($extension, $property = null)
    {
        $this->extension = $extension;
        $this->property  = $property;
    }

    /**
     * @param mixed $item
     *
     * @return bool
     */
    public function filter($item)
    {
        if ($this->property === null) {
            $filename = $item;
        } else {
            $filename = \igorw\get_in($item, $this->property);
        }

        if (preg_match(sprintf('/\.%s/', $this->extension), $filename) === 1) {
            return true;
        }

        return false;
    }
}
