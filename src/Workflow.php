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
use Plum\Plum\Pipe\ConverterPipe;
use Plum\Plum\Pipe\FilterPipe;
use Plum\Plum\Pipe\Pipe;
use Plum\Plum\Pipe\WriterPipe;
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

    /** @var Pipe[] */
    private $pipeline = [];

    /**
     * @param string|null $type
     *
     * @return Pipe[]
     */
    public function getPipeline($type = null)
    {
        $pipeline = [];

        foreach ($this->pipeline as $element) {
            if ($type === null || $element->getType() === $type) {
                $pipeline[] = $element;
            }
        }

        return $pipeline;
    }

    /**
     * Inserts an element into the pipeline at the given position.
     *
     * @param Pipe $pipe
     *
     * @return Workflow
     */
    protected function addPipe(Pipe $pipe)
    {
        if ($pipe->getPosition() === self::PREPEND) {
            array_unshift($this->pipeline, $pipe);
        } else {
            $this->pipeline[] = $pipe;
        }

        return $this;
    }

    /**
     * @param array|callable|FilterInterface $element
     *
     * @return Workflow
     *
     * @throws InvalidArgumentException
     */
    public function addFilter($element)
    {
        $pipe = FilterPipe::createFilter($element);

        return $this->addPipe($pipe);
    }

    /**
     * @return FilterPipe[]
     */
    public function getFilters()
    {
        return $this->getPipeline(self::PIPELINE_TYPE_FILTER);
    }

    /**
     * @return FilterPipe[]
     */
    public function getValueFilters()
    {
        return $this->getPipeline(self::PIPELINE_TYPE_VALUE_FILTER);
    }

    /**
     * @param ConverterInterface|callable|array $element
     *
     * @return Workflow $this
     *
     */
    public function addConverter($element)
    {
        $pipe = ConverterPipe::createConverter($element);

        return $this->addPipe($pipe);
    }

    /**
     * @return ConverterPipe[]
     */
    public function getConverters()
    {
        return $this->getPipeline(self::PIPELINE_TYPE_CONVERTER);
    }

    /**
     * @return ConverterPipe[]
     */
    public function getValueConverters()
    {
        return $this->getPipeline(self::PIPELINE_TYPE_VALUE_CONVERTER);
    }

    /**
     * @param WriterInterface|array $element
     *
     * @return Workflow
     *
     */
    public function addWriter($element)
    {
        $pipe = WriterPipe::createWriter($element);

        return $this->addPipe($pipe);
    }

    /**
     * @return WriterPipe[]
     */
    public function getWriters()
    {
        return $this->getPipeline(self::PIPELINE_TYPE_WRITER);
    }

    /**
     * @param ReaderInterface[]|ReaderInterface $readers
     *
     * @return Result
     */
    public function process($readers)
    {
        if (!is_array($readers)) {
            $readers = [$readers];
        }

        $result = new Result();

        $this->prepareWriters($this->getWriters());

        foreach ($readers as $reader) {
            $this->processReader($reader, $result);
        }

        $this->finishWriters($this->getWriters());

        return $result;
    }

    /**
     * @param ReaderInterface $reader
     * @param Result          $result
     */
    protected function processReader(ReaderInterface $reader, Result $result)
    {
        foreach ($reader as $item) {
            $result->incReadCount();
            try {
                $this->processItem($item, $result);
            } catch (\Exception $e) {
                $result->addException($e);
            }
        }
    }

    /**
     * @param WriterPipe[] $writers
     *
     * @return void
     */
    protected function prepareWriters($writers)
    {
        foreach ($writers as $element) {
            $element->getWriter()->prepare();
        }
    }

    /**
     * @param WriterPipe[] $writers
     *
     * @return void
     */
    protected function finishWriters($writers)
    {
        foreach ($writers as $element) {
            $element->getWriter()->finish();
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
            if ($element->getType() === self::PIPELINE_TYPE_FILTER) {
                if ($element->getFilter()->filter($item) === false) {
                    return;
                }
            } else if ($element->getType() === self::PIPELINE_TYPE_VALUE_FILTER) {
                if ($element->getFilter()->filter(Vale::get($item, $element->getField())) === false) {
                    return;
                }
            } else if ($element->getType() === self::PIPELINE_TYPE_CONVERTER) {
                $item = $this->convertItem(
                    $item,
                    $element->getConverter(),
                    $element->getFilter(),
                    $element->getFilterField()
                );
                if ($item === null) {
                    return;
                }
            } else if ($element->getType() === self::PIPELINE_TYPE_VALUE_CONVERTER) {
                $item = $this->convertItemValue(
                    $item,
                    $element->getField(),
                    $element->getConverter(),
                    $element->getFilter(),
                    $element->getFilterField()
                );
            } else if ($element->getType() === self::PIPELINE_TYPE_WRITER) {
                if ($this->writeItem($item, $element->getWriter(), $element->getFilter()) === true) {
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
     * @param mixed                $item
     * @param string|array         $field
     * @param ConverterInterface   $converter
     * @param FilterInterface|null $filter
     * @param string|array|null    $filterField
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
        $value       = Vale::get($item, $field);
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
