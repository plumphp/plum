<?php

/**
 * This file is part of plumphp/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plum\Plum;

use Cocur\Vale\Vale;
use InvalidArgumentException;
use Plum\Plum\Converter\CallbackConverter;
use Plum\Plum\Converter\ConverterInterface;
use Plum\Plum\Filter\CallbackFilter;
use Plum\Plum\Filter\FilterInterface;
use Plum\Plum\Reader\ReaderInterface;
use Plum\Plum\Writer\WriterInterface;

/**
 * Workflow
 *
 * @package   Plum\Plum
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014-2015 Florian Eckerstorfer
 */
class Workflow
{
    const PIPELINE_TYPE_FILTER          = 1;
    const PIPELINE_TYPE_CONVERTER       = 2;
    const PIPELINE_TYPE_WRITER          = 3;
    const PIPELINE_TYPE_VALUE_FILTER    = 4;
    const PIPELINE_TYPE_VALUE_CONVERTER = 5;

    const APPEND  = 1;
    const PREPEND = 2;

    /** @var PipelineInterface[][] */
    private $pipeline = [];

    /**
     * @param string|null $type
     *
     * @return PipelineInterface[]
     */
    public function getPipeline($type = null)
    {
        $pipeline = [];

        foreach ($this->pipeline as $element) {
            if ($type === null || $element['type'] === $type) {
                $pipeline[] = $element;
            }
        }

        return $pipeline;
    }

    /**
     * Inserts an element into the pipeline at the given position.
     *
     * @param array $element
     *
     * @return Workflow
     */
    protected function insertElement($element)
    {
        $position = isset($element['position']) ? $element['position'] : self::APPEND;

        if ($position === self::PREPEND) {
            array_unshift($this->pipeline, $element);
        } else {
            $this->pipeline[] = $element;
        }

        return $this;
    }

    /**
     * @param array|FilterInterface $element
     *
     * @return Workflow
     *
     * @throws InvalidArgumentException
     */
    public function addFilter($element)
    {
        if (is_callable($element)) {
            $element = new CallbackFilter($element);
        } else if (is_array($element) && isset($element['filter']) && is_callable($element['filter'])) {
            $element['filter'] = new CallbackFilter($element['filter']);
        }
        if ($element instanceof FilterInterface) {
            $element = ['filter' => $element];
        } else if (!is_array($element) || !isset($element['filter'])
                || !$element['filter'] instanceof FilterInterface) {
            throw new InvalidArgumentException('Workflow::addFilter() must be called with either an instance of "Plum\Plum\Filter\FilterInterface" or an array that contains "filter".');
        }

        $element = array_merge([
            'type'     => self::PIPELINE_TYPE_FILTER,
            'position' => self::APPEND
        ], $element);

        return $this->insertElement($element);
    }

    /**
     * @return FilterInterface[]
     */
    public function getFilters()
    {
        return $this->getPipeline(self::PIPELINE_TYPE_FILTER);
    }

    /**
     * @param array|FilterInterface $element
     * @param string|null           $field
     *
     * @return Workflow
     *
     * @throws InvalidArgumentException
     */
    public function addValueFilter($element, $field = null)
    {
        if (is_callable($element)) {
            $element = new CallbackFilter($element);
        } else if (is_array($element) && isset($element['filter']) && is_callable($element['filter'])) {
            $element['filter'] = new CallbackFilter($element['filter']);
        }
        if ($element instanceof FilterInterface && $field) {
            $element = ['filter' => $element, 'field' => $field];
        } else if (!is_array($element) || (!is_array($element) && !$field) || !isset($element['filter'])
                || !isset($element['field']) || !$element['filter'] instanceof FilterInterface) {
            throw new InvalidArgumentException('Workflow::addValueFilter() must be called with either an instance of "Plum\Plum\Filter\FilterInterface" and a field name or with an array that contains "filter" and "field".');
        }

        $element = array_merge([
            'type'     => self::PIPELINE_TYPE_VALUE_FILTER,
            'position' => self::APPEND
        ], $element);

        return $this->insertElement($element);
    }

    /**
     * @return FilterInterface[]
     */
    public function getValueFilters()
    {
        return $this->getPipeline(self::PIPELINE_TYPE_VALUE_FILTER);
    }

    /**
     * @param array|ConverterInterface|callback $element
     *
     * @return Workflow $this
     *
     * @throws InvalidArgumentException
     */
    public function addConverter($element)
    {
        if (is_callable($element)) {
            $element = new CallbackConverter($element);
        } else if (is_array($element) && isset($element['converter']) && is_callable($element['converter'])) {
            $element['converter'] = new CallbackConverter($element['converter']);
        }
        if (is_array($element) && isset($element['filter']) && is_callable($element['filter'])) {
            $element['filter'] = new CallbackFilter($element['filter']);
        }

        if ($element instanceof ConverterInterface) {
            $element = ['converter' => $element];
        } else if (!is_array($element) || !isset($element['converter'])
                || !$element['converter'] instanceof ConverterInterface) {
            throw new InvalidArgumentException('Workflow::addConverter() must be called with either an instance of "Plum\Plum\Converter\ConverterInterface" or with an array that contains "converter".');
        }

        $element = array_merge([
            'type'        => self::PIPELINE_TYPE_CONVERTER,
            'filter'      => null,
            'filterField' => null,
            'position'    => self::APPEND
        ], $element);

        return $this->insertElement($element);
    }

    /**
     * @return ConverterInterface[]
     */
    public function getConverters()
    {
        return $this->getPipeline(self::PIPELINE_TYPE_CONVERTER);
    }

    /**
     * @param array|ConverterInterface|callback $element
     * @param string|null                       $field
     *
     * @return Workflow
     *
     * @throws InvalidArgumentException
     */
    public function addValueConverter($element, $field = null)
    {
        if (is_callable($element)) {
            $element = new CallbackConverter($element);
        } else if (is_array($element) && isset($element['converter']) && is_callable($element['converter'])) {
            $element['converter'] = new CallbackConverter($element['converter']);
        }
        if (is_array($element) && isset($element['filter']) && is_callable($element['filter'])) {
            $element['filter'] = new CallbackFilter($element['filter']);
        }

        if ($element instanceof ConverterInterface && $field) {
            $element = ['converter' => $element, 'field' => $field];
        } else if (!is_array($element) || (!is_array($element) && !$field) || !isset($element['converter'])
            || !isset($element['field']) || !$element['converter'] instanceof ConverterInterface) {
            throw new InvalidArgumentException('Workflow::addValueConverter() must be called with either an instance of "Plum\Plum\Converter\ConverterInterface" and a field name or with an array that contains "converter" and "field".');
        }

        $element = array_merge([
            'type'        => self::PIPELINE_TYPE_VALUE_CONVERTER,
            'filter'      => null,
            'filterField' => null,
            'position'    => self::APPEND
        ], $element);

        return $this->insertElement($element);
    }

    /**
     * @return ConverterInterface[]
     */
    public function getValueConverters()
    {
        return $this->getPipeline(self::PIPELINE_TYPE_VALUE_CONVERTER);
    }

    /**
     * @param array|WriterInterface $element
     *
     * @return Workflow
     *
     * @throws InvalidArgumentException
     */
    public function addWriter($element)
    {
        if (is_array($element) && isset($element['filter']) && is_callable($element['filter'])) {
            $element['filter'] = new CallbackFilter($element['filter']);
        }
        if ($element instanceof WriterInterface) {
            $element = ['writer' => $element];
        } else if (!is_array($element) || !isset($element['writer'])
                || !$element['writer'] instanceof WriterInterface) {
            throw new InvalidArgumentException('Workflow::addWriter() must be called with either an instance of "Plum\Plum\Writer\WriterInterface" or with an array that contains "writer".');
        }

        $element = array_merge([
            'type'     => self::PIPELINE_TYPE_WRITER,
            'filter'   => null,
            'position' => self::APPEND
        ], $element);

        return $this->insertElement($element);
    }

    /**
     * @return WriterInterface[]
     */
    public function getWriters()
    {
        return $this->getPipeline(self::PIPELINE_TYPE_WRITER);
    }

    /**
     * @param ReaderInterface $reader
     *
     * @return Result
     */
    public function process(ReaderInterface $reader)
    {
        $result = new Result();

        $this->prepareWriters($this->getWriters());

        foreach ($reader as $item) {
            $result->incReadCount();
            try {
                $this->processItem($item, $result);
            } catch (\Exception $e) {
                $result->addException($e);
            }
        }

        $this->finishWriters($this->getWriters());

        return $result;
    }

    /**
     * @param WriterInterface[] $writers
     *
     * @return void
     */
    protected function prepareWriters($writers)
    {
        foreach ($writers as $element) {
            $element['writer']->prepare();
        }
    }

    /**
     * @param WriterInterface[] $writers
     *
     * @return void
     */
    protected function finishWriters($writers)
    {
        foreach ($writers as $element) {
            $element['writer']->finish();
        }
    }

    /**
     * @param mixed  $item
     * @param Result $result
     *
     * @return void
     */
    protected function processItem($item, Result $result)
    {
        $written = false;

        foreach ($this->pipeline as $element) {
            if ($element['type'] === self::PIPELINE_TYPE_FILTER) {
                if ($element['filter']->filter($item) === false) {
                    return;
                }
            } else if ($element['type'] === self::PIPELINE_TYPE_VALUE_FILTER) {
                if ($element['filter']->filter(Vale::get($item, $element['field'])) === false) {
                    return;
                }
            } else if ($element['type'] === self::PIPELINE_TYPE_CONVERTER) {
                $item = $this->convertItem($item, $element['converter'], $element['filter'], $element['filterField']);
                if ($item === null) {
                    return;
                }
            } else if ($element['type'] === self::PIPELINE_TYPE_VALUE_CONVERTER) {
                $item = $this->convertItemValue(
                    $item,
                    $element['field'],
                    $element['converter'],
                    $element['filter'],
                    $element['filterField']
                );
            } else if ($element['type'] === self::PIPELINE_TYPE_WRITER) {
                if ($this->writeItem($item, $element['writer'], $element['filter']) === true) {
                    $result->incWriteCount();
                    $written = true;
                }
            }
        }

        if ($written === true) {
            $result->incItemWriteCount();
        }
    }

    /**
     * Applies the given converter to the given item either if no filter is given or if the filter returns `true`.
     *
     * @param mixed                $item
     * @param ConverterInterface   $converter
     * @param FilterInterface|null $filter
     * @param string|array|null    $filterField
     *
     * @return mixed
     */
    protected function convertItem(
        $item,
        ConverterInterface $converter,
        FilterInterface $filter = null,
        $filterField = null
    ) {
        $filterValue = $filterField ? Vale::get($item, $filterField) : $item;
        if ($filter === null || $filter->filter($filterValue) === true) {
            return $converter->convert($item);
        }

        return $item;
    }

    /**
     * Applies the given converter to the given field in the given item if no filter is given or if the filters returns
     * `true` for the field.
     *
     * @param mixed              $item
     * @param string|array       $field
     * @param ConverterInterface $converter
     * @param FilterInterface    $filter
     * @param string|array|null  $filterField
     *
     * @return mixed
     */
    protected function convertItemValue(
        $item,
        $field,
        ConverterInterface $converter,
        FilterInterface $filter = null,
        $filterField = null
    ) {
        $value = Vale::get($item, $field);
        $filterValue = $filterField ? Vale::get($item, $filterField) : $item;
        if ($filter === null || $filter->filter($filterValue) === true) {
            $item = Vale::set($item, $field, $converter->convert($value));
        }

        return $item;
    }

    /**
     * Writes the given item to the given writer if the no filter is given or the filter returns `true`.
     *
     * @param mixed                $item
     * @param WriterInterface      $writer
     * @param FilterInterface|null $filter
     *
     * @return bool `true` if the item has been written, `false` if not.
     */
    protected function writeItem($item, WriterInterface $writer, FilterInterface $filter = null)
    {
        if ($filter === null || $filter->filter($item) === true) {
            $writer->writeItem($item);

            return true;
        }

        return false;
    }
}
