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
use Plum\Plum\Converter\CallbackConverter;
use Plum\Plum\Converter\ConverterInterface;
use Plum\Plum\Filter\CallbackFilter;

/**
 * ConverterPipe
 *
 * @package   Plum\Plum\Pipe
 * @author    Florian Eckerstorfer
 * @copyright 2014-2015 Florian Eckerstorfer
 */
class ConverterPipe extends Pipe
{
    /**
     * @param ConverterInterface|callable|array $element
     *
     * @return ConverterPipe
     */
    public static function createConverter($element)
    {
        if (is_callable($element)) {
            $converter = new CallbackConverter($element);
        } else if (self::elementHasCallbackConverter($element)) {
            $converter = new CallbackConverter($element['converter']);
        } else if ($element instanceof ConverterInterface) {
            $converter = $element;
        } else if (self::elementHasConverter($element)) {
            $converter = $element['converter'];
        } else {
            throw new InvalidArgumentException('Workflow::addConverter() must be called with either an instance of '.
                                               '"Plum\Plum\Converter\ConverterInterface" or with an array that '.
                                               'contains "converter".');
        }

        $pipe = new self($element);
        $pipe->converter = $converter;
        if (is_array($element) && isset($element['field'])) {
            $pipe->setField($element['field']);
            $pipe->setType(self::PIPELINE_TYPE_VALUE_CONVERTER);
        } else {
            $pipe->setType(self::PIPELINE_TYPE_CONVERTER);
        }

        if (is_array($element) && isset($element['filter']) && is_callable($element['filter'])) {
            $pipe->setFilter(new CallbackFilter($element['filter']));
        } else if (is_array($element) && isset($element['filter'])) {
            $pipe->setFilter($element['filter']);
        }
        if (is_array($element) && isset($element['filterField'])) {
            $pipe->setFilterField($element['filterField']);
        }

        return $pipe;
    }

    /**
     * @param array $element
     *
     * @return bool
     */
    protected static function elementHasCallbackConverter($element)
    {
        return is_array($element) && isset($element['converter']) && is_callable($element['converter']);
    }

    /**
     * @param array $element
     *
     * @return bool
     */
    protected static function elementHasConverter($element)
    {
        return is_array($element) &&
               isset($element['converter']) &&
               $element['converter'] instanceof ConverterInterface;
    }
}
