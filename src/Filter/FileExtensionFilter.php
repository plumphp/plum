<?php

/**
 * This file is part of cocur/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocur\Plum\Filter;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * FileExtensionFilter
 *
 * @package   Cocur\Plum\Converter
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 */
class FileExtensionFilter implements FilterInterface
{
    /** @var string */
    private $extension;

    /** @var string|null */
    private $property;

    /** @var PropertyAccessor */
    private $accessor;

    /**
     * @param string        $extension
     * @param string|null $property
     */
    public function __construct($extension, $property = null)
    {
        $this->extension = $extension;
        $this->property  = $property;
        $this->accessor  = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @param mixed $item
     *
     * @return bool
     */
    public function filter($item)
    {
        $filename = $this->property === null ? $item : $this->accessor->getValue($item, $this->property);

        return preg_match(sprintf('/\.%s/', $this->extension), $filename) === 1;
    }
}
