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
use Plum\Plum\Writer\WriterInterface;

/**
 * WriterPipe
 *
 * @package   Plum\Plum\Pipe
 * @author    Florian Eckerstorfer
 * @copyright 2014-2015 Florian Eckerstorfer
 */
class WriterPipe extends Pipe
{
    /**
     * @param WriterInterface|array $element
     *
     * @return WriterPipe
     */
    public static function createWriter($element)
    {
        if ($element instanceof WriterInterface) {
            $writer = $element;
        } else if (isset($element['writer']) && $element['writer'] instanceof WriterInterface) {
            $writer = $element['writer'];
        } else {
            throw new InvalidArgumentException('Workflow::addWriter() must be called with either an instance of '.
                                               '"Plum\Plum\Writer\WriterInterface" or with an array that contains '.
                                               '"writer".');
        }

        $pipe         = new self($element);
        $pipe->type   = self::PIPELINE_TYPE_WRITER;
        $pipe->writer = $writer;
        if (is_array($element) && isset($element['filter']) && is_callable($element['filter'])) {
            $pipe->setFilter(new CallbackFilter($element['filter']));
        } else if (is_array($element) && isset($element['filter'])) {
            $pipe->setFilter($element['filter']);
        }

        return $pipe;
    }
}
