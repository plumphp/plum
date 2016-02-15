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

use Plum\Plum\Converter\ConverterInterface;
use Plum\Plum\Filter\CallbackFilter;
use Plum\Plum\Filter\FilterInterface;
use Plum\Plum\Workflow;
use Plum\Plum\Writer\WriterInterface;

/**
 * Pipe.
 *
 * @author    Florian Eckerstorfer
 * @copyright 2014-2016 Florian Eckerstorfer
 */
abstract class AbstractPipe
{
    /**
     * @var int
     */
    protected $position = Workflow::APPEND;

    /**
     * @var FilterInterface
     */
    protected $filter;

    /**
     * @var ConverterInterface
     */
    protected $converter;

    /**
     * @var WriterInterface
     */
    protected $writer;

    /**
     * @var string|int|array
     */
    protected $field;

    /**
     * @var string|int|array
     */
    protected $filterField;

    /**
     * @param mixed $element
     */
    public function __construct($element)
    {
        if (is_array($element) && isset($element['position'])) {
            $this->setPosition($element['position']);
        }
        if (is_array($element) && isset($element['field'])) {
            $this->setField($element['field']);
        }
        if (is_array($element) && isset($element['filterField'])) {
            $this->setFilterField($element['filterField']);
        }
        if (is_array($element) && isset($element['filter']) && is_callable($element['filter'])) {
            $this->setFilter(new CallbackFilter($element['filter']));
        } elseif (is_array($element) && isset($element['filter'])) {
            $this->setFilter($element['filter']);
        }
    }

    /**
     * @return FilterInterface
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @return ConverterInterface
     */
    public function getConverter()
    {
        return $this->converter;
    }

    /**
     * @return WriterInterface
     */
    public function getWriter()
    {
        return $this->writer;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return array|int|string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return array|int|string
     */
    public function getFilterField()
    {
        return $this->filterField;
    }

    /**
     * @param int $position
     *
     * @return AbstractPipe
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @param FilterInterface $filter
     *
     * @return AbstractPipe
     */
    public function setFilter(FilterInterface $filter)
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * @param array|int|string $filterField
     *
     * @return $this
     */
    public function setFilterField($filterField)
    {
        $this->filterField = $filterField;

        return $this;
    }

    /**
     * @param array|int|string $field
     *
     * @return $this
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }
}
