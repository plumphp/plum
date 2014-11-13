<?php

/**
 * This file is part of cocur/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocur\Plum;

use Cocur\Plum\Converter\ConverterInterface;
use Cocur\Plum\Filter\FilterInterface;
use Cocur\Plum\Reader\ReaderInterface;
use Cocur\Plum\Writer\WriterInterface;

/**
 * Workflow
 *
 * @package   Cocur\Plum
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 */
class Workflow
{
    const PIPELINE_TYPE_FILTER    = 1;
    const PIPELINE_TYPE_CONVERTER = 2;
    const PIPELINE_TYPE_WRITER    = 3;

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
            if ($type === null || $element[0] === $type) {
                $pipeline[] = $element[1];
            }
        }

        return $pipeline;
    }

    /**
     * @param FilterInterface $filter
     *
     * @return Workflow
     */
    public function addFilter(FilterInterface $filter)
    {
        $this->pipeline[] = [self::PIPELINE_TYPE_FILTER, $filter];

        return $this;
    }

    /**
     * @return FilterInterface[]
     */
    public function getFilters()
    {
        return $this->getPipeline(self::PIPELINE_TYPE_FILTER);
    }

    /**
     * @param ConverterInterface   $converter
     * @param FilterInterface|null $filter
     *
     * @return Workflow $this
     */
    public function addConverter(ConverterInterface $converter, FilterInterface $filter = null)
    {
        $this->pipeline[] = [self::PIPELINE_TYPE_CONVERTER, $converter, $filter];

        return $this;
    }

    /**
     * @return ConverterInterface[]
     */
    public function getConverters()
    {
        return $this->getPipeline(self::PIPELINE_TYPE_CONVERTER);
    }

    /**
     * @param WriterInterface $writer
     *
     * @return Workflow
     */
    public function addWriter(WriterInterface $writer)
    {
        $this->pipeline[] = [self::PIPELINE_TYPE_WRITER, $writer];

        return $this;
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
        foreach ($writers as $writer) {
            $writer->prepare();
        }
    }

    /**
     * @param WriterInterface[] $writers
     *
     * @return void
     */
    protected function finishWriters($writers)
    {
        foreach ($writers as $writer) {
            $writer->finish();
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
            if ($element[0] === self::PIPELINE_TYPE_FILTER) {
                if ($element[1]->filter($item) === false) {
                    return;
                }
            } else if ($element[0] === self::PIPELINE_TYPE_CONVERTER) {
                $item = $this->applyConverter($item, $element[1], $element[2]);
            } else if ($element[0] === self::PIPELINE_TYPE_WRITER) {
                $element[1]->writeItem($item);
                $result->incWriteCount();
                $written = true;
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
     *
     * @return mixed
     */
    protected function applyConverter($item, ConverterInterface $converter, FilterInterface $filter = null)
    {
        if ($filter === null || $filter->filter($item) === true) {
            return $converter->convert($item);
        }

        return $item;
    }
}
