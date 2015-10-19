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

/**
 * ConverterPipe
 *
 * @package   Plum\Plum\Pipe
 * @author    Florian Eckerstorfer
 * @copyright 2014-2015 Florian Eckerstorfer
 */
class ConverterPipe extends AbstractPipe
{
    protected $type = AbstractPipe::TYPE_CONVERTER;

    /**
     * @param ConverterInterface|callable|array $element
     *
     * @return ConverterPipe
     */
    public static function createConverter($element)
    {
        if (is_callable($element)) {
            $converter = new CallbackConverter($element);
        } else if (self::hasElementCallbackConverter($element)) {
            $converter = new CallbackConverter($element['converter']);
        } else if ($element instanceof ConverterInterface) {
            $converter = $element;
        } else if (self::hasElementConverter($element)) {
            $converter = $element['converter'];
        } else {
            throw new InvalidArgumentException('Workflow::addConverter() must be called with either an instance of '.
                                               '"Plum\Plum\Converter\ConverterInterface" or with an array that '.
                                               'contains "converter".');
        }

        $pipe            = new self($element);
        $pipe->converter = $converter;



        return $pipe;
    }

    /**
     * @param array|callable|ConverterInterface $element
     *
     * @return bool
     */
    protected static function hasElementCallbackConverter($element)
    {
        return is_array($element) && isset($element['converter']) && is_callable($element['converter']);
    }

    /**
     * @param array|callable|ConverterInterface $element
     *
     * @return bool
     */
    protected static function hasElementConverter($element)
    {
        return is_array($element)
               && isset($element['converter'])
               && $element['converter'] instanceof ConverterInterface;
    }
}
