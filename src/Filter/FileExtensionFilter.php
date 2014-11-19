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
    /** @var string[] */
    private $extensions;

    /** @var string|null */
    private $property;

    /** @var PropertyAccessor */
    private $accessor;

    /**
     * @param string|string[] $extension
     * @param string|null     $property
     */
    public function __construct($extension, $property = null)
    {
        if (is_string($extension) === true) {
            $extension = [$extension];
        }
        $this->extensions = $extension;
        $this->property   = $property;
        $this->accessor   = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @param mixed $item
     *
     * @return bool
     */
    public function filter($item)
    {
        $filename = $this->property === null ? $item : $this->accessor->getValue($item, $this->property);

        foreach ($this->extensions as $extension) {
            if (preg_match(sprintf('/\.%s/', $extension), $filename) === 1) {
                return true;
            }
        }

        return false;
    }
}
