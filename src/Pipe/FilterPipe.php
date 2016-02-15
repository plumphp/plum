<?php

/**
 * This file is part of plumphp/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plum\Plum\Pipe;

use InvalidArgumentException;
use Plum\Plum\Filter\CallbackFilter;
use Plum\Plum\Filter\FilterInterface;

/**
 * FilterPipe.
 *
 * @author    Florian Eckerstorfer
 * @copyright 2014-2016 Florian Eckerstorfer
 */
class FilterPipe extends AbstractPipe
{
    /**
     * @param FilterInterface|callable|array $element
     *
     * @return FilterPipe
     */
    public static function createFilter($element)
    {
        if (is_callable($element)) {
            $filter = new CallbackFilter($element);
        } elseif (is_array($element) && isset($element['filter']) && is_callable($element['filter'])) {
            $filter = new CallbackFilter($element['filter']);
            unset($element['filter']);
        } elseif (is_array($element) && isset($element['filter']) && $element['filter'] instanceof FilterInterface) {
            $filter = $element['filter'];
            unset($element['filter']);
        } elseif ($element instanceof FilterInterface) {
            $filter = $element;
        } else {
            throw new InvalidArgumentException('Workflow::addFilter() must be called with either an instance of '.
                                               '"Plum\Plum\Filter\FilterInterface" or an array that contains '.
                                               '"filter".');
        }

        $pipe         = new self($element);
        $pipe->filter = $filter;

        return $pipe;
    }
}
