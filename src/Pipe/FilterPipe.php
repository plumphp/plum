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
 * FilterPipe
 *
 * @package   Plum\Plum\Pipe
 * @author    Florian Eckerstorfer
 * @copyright 2014-2015 Florian Eckerstorfer
 */
class FilterPipe extends AbstractPipe
{
    protected $type = AbstractPipe::TYPE_FILTER;

    /**
     * @param FilterInterface|callable|array $element
     *
     * @return FilterPipe
     */
    public static function createFilter($element)
    {
        if (is_callable($element)) {
            $filter = new CallbackFilter($element);
        } else if (is_array($element) && isset($element['filter']) && is_callable($element['filter'])) {
            $filter = new CallbackFilter($element['filter']);
            unset($element['filter']);
        } else if (is_array($element) && isset($element['filter']) && $element['filter'] instanceof FilterInterface) {
            $filter = $element['filter'];
            unset($element['filter']);
        } else if ($element instanceof FilterInterface) {
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
