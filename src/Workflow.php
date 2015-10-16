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
     * @param array $pipe
     *
     * @return Workflow
     */
    protected function addPipe($pipe)
    {
        $position = isset($pipe['position']) ? $pipe['position'] : self::APPEND;

        if ($position === self::PREPEND) {
            array_unshift($this->pipeline, $pipe);
        } else {
            $this->pipeline[] = $pipe;
        }

        return $this;
    }

    /**
     * @param array|FilterInterface $pipe
     *
     * @return Workflow
     *
     * @throws InvalidArgumentException
     */
    public function addFilter($pipe)
    {
        if (is_callable($pipe)) {
            $pipe = new CallbackFilter($pipe);
        } else if (is_array($pipe) && isset($pipe['filter']) && is_callable($pipe['filter'])) {
            $pipe['filter'] = new CallbackFilter($pipe['filter']);
        }
        if ($pipe instanceof FilterInterface) {
            $pipe = ['filter' => $pipe];
        } else if (!is_array($pipe) || !isset($pipe['filter'])
                || !$pipe['filter'] instanceof FilterInterface) {
            throw new InvalidArgumentException('Workflow::addFilter() must be called with either an instance of '.
                                               '"Plum\Plum\Filter\FilterInterface" or an array that contains '.
                                               '"filter".');
        }

        $pipe = array_merge([
            'type'     => isset($pipe['field']) ? self::PIPELINE_TYPE_VALUE_FILTER : self::PIPELINE_TYPE_FILTER,
            'position' => self::APPEND
        ], $pipe);

        return $this->addPipe($pipe);
    }

    /**
     * @return FilterInterface[]
     */
    public function getFilters()
    {
        return $this->getPipeline(self::PIPELINE_TYPE_FILTER);
    }

    /**
     * @return FilterInterface[]
     */
    public function getValueFilters()
    {
        return $this->getPipeline(self::PIPELINE_TYPE_VALUE_FILTER);
    }

    /**
     * @param array|ConverterInterface|callback $pipe
     *
     * @return Workflow $this
     *
     * @throws InvalidArgumentException
     */
    public function addConverter($pipe)
    {
        if (is_callable($pipe)) {
            $pipe = new CallbackConverter($pipe);
        } else if (is_array($pipe) && isset($pipe['converter']) && is_callable($pipe['converter'])) {
            $pipe['converter'] = new CallbackConverter($pipe['converter']);
        }
        if (is_array($pipe) && isset($pipe['filter']) && is_callable($pipe['filter'])) {
            $pipe['filter'] = new CallbackFilter($pipe['filter']);
        }

        if ($pipe instanceof ConverterInterface) {
            $pipe = ['converter' => $pipe];
        } else if (!is_array($pipe) || !isset($pipe['converter'])
                || !$pipe['converter'] instanceof ConverterInterface) {
            throw new InvalidArgumentException('Workflow::addConverter() must be called with either an instance of '.
                                               '"Plum\Plum\Converter\ConverterInterface" or with an array that '.
                                               'contains "converter".');
        }

        $pipe = array_merge([
            'type'        => isset($pipe['field']) ?
                self::PIPELINE_TYPE_VALUE_CONVERTER :
                self::PIPELINE_TYPE_CONVERTER,
            'filter'      => null,
            'filterField' => null,
            'position'    => self::APPEND
        ], $pipe);

        return $this->addPipe($pipe);
    }

    /**
     * @return ConverterInterface[]
     */
    public function getConverters()
    {
        return $this->getPipeline(self::PIPELINE_TYPE_CONVERTER);
    }

    /**
     * @return ConverterInterface[]
     */
    public function getValueConverters()
    {
        return $this->getPipeline(self::PIPELINE_TYPE_VALUE_CONVERTER);
    }

    /**
     * @param array|WriterInterface $pipe
     *
     * @return Workflow
     *
     * @throws InvalidArgumentException
     */
    public function addWriter($pipe)
    {
        if (is_array($pipe) && isset($pipe['filter']) && is_callable($pipe['filter'])) {
            $pipe['filter'] = new CallbackFilter($pipe['filter']);
        }
        if ($pipe instanceof WriterInterface) {
            $pipe = ['writer' => $pipe];
        } else if (!is_array($pipe) || !isset($pipe['writer'])
                || !$pipe['writer'] instanceof WriterInterface) {
            throw new InvalidArgumentException('Workflow::addWriter() must be called with either an instance of '.
                                               '"Plum\Plum\Writer\WriterInterface" or with an array that contains '.
                                               '"writer".');
        }

        $pipe = array_merge([
            'type'     => self::PIPELINE_TYPE_WRITER,
            'filter'   => null,
            'position' => self::APPEND
        ], $pipe);

        return $this->addPipe($pipe);
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
