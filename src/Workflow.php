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
use Plum\Plum\Converter\ConverterInterface;
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
        return $this->getPipeline(Pipe::TYPE_FILTER);
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
        return $this->getPipeline(Pipe::TYPE_CONVERTER);
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
        return $this->getPipeline(Pipe::TYPE_WRITER);
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
            if ($element instanceof FilterPipe && $element->getField()) {
                if ($element->getFilter()->filter(Vale::get($item, $element->getField())) === false) {
                    return;
                }
            } else if ($element instanceof FilterPipe) {
                if ($element->getFilter()->filter($item) === false) {
                    return;
                }
            } else if ($element instanceof ConverterPipe && $element->getField()) {
                $item = $this->convertItemValue($item, $element);
            } else if ($element instanceof ConverterPipe) {
                $item = $this->convertItem($item, $element);
                if ($item === null) {
                    return;
                }
            } else if ($element instanceof WriterPipe) {
                if ($this->writeItem($item, $element) === true) {
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
     * @param mixed         $item
     * @param ConverterPipe $pipe
     *
     * @return mixed
     *
     */
    protected function convertItem($item, ConverterPipe $pipe)
    {
        $filterValue = $pipe->getFilterField() ? Vale::get($item, $pipe->getFilterField()) : $item;
        if ($pipe->getFilter() === null || $pipe->getFilter()->filter($filterValue) === true) {
            return $pipe->getConverter()->convert($item);
        }

        return $item;
    }

    /**
     * Applies the given converter to the given field in the given item if no filter is given or if the filters returns
     * `true` for the field.
     *
     * @param mixed         $item
     * @param ConverterPipe $pipe
     *
     * @return mixed
     *
     */
    protected function convertItemValue($item, ConverterPipe $pipe)
    {
        $value       = Vale::get($item, $pipe->getField());
        $filterValue = $pipe->getFilterField() ? Vale::get($item, $pipe->getFilterField()) : $item;

        if ($pipe->getFilter() === null || $pipe->getFilter()->filter($filterValue) === true) {
            $item = Vale::set($item, $pipe->getField(), $pipe->getConverter()->convert($value));
        }

        return $item;
    }

    /**
     * Writes the given item to the given writer if the no filter is given or the filter returns `true`.
     *
     * @param mixed      $item
     * @param WriterPipe $pipe
     *
     * @return bool `true` if the item has been written, `false` if not.
     *
     */
    protected function writeItem($item, WriterPipe $pipe)
    {
        if ($pipe->getFilter() === null || $pipe->getFilter()->filter($item) === true) {
            $pipe->getWriter()->writeItem($item);

            return true;
        }

        return false;
    }
}
