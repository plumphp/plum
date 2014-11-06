<?php

/**
 * This file is part of florianeckerstorfer/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FlorianEc\Plum;

use FlorianEc\Plum\Converter\ConverterInterface;
use FlorianEc\Plum\Filter\FilterInterface;
use FlorianEc\Plum\Reader\ReaderInterface;
use FlorianEc\Plum\Writer\WriterInterface;

/**
 * Workflow
 *
 * @package   FlorianEc\Plum
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
        $readCount  = 0;
        $writeCount = 0;

        foreach ($this->getWriters() as $writer) {
            $writer->prepare();
        }

        foreach ($reader as $item) {
            $readCount++;
            $writeCount += $this->processItem($item);
        }

        foreach ($this->getWriters() as $writer) {
            $writer->finish();
        }

        return new Result($readCount, $writeCount);
    }

    /**
     * @param $item
     *
     * @return int
     */
    protected function processItem($item)
    {
        $writeCount = 0;

        foreach ($this->pipeline as $element) {
            if ($element[0] === self::PIPELINE_TYPE_FILTER) {
                if ($element[1]->filter($item) === false) {
                    return 0;
                }
            } else if ($element[0] === self::PIPELINE_TYPE_CONVERTER) {
                $item = $this->applyConverter($item, $element[1], $element[2]);
            } else if ($element[0] === self::PIPELINE_TYPE_WRITER) {
                $element[1]->writeItem($item);
                $writeCount = 1;
            }
        }

        return $writeCount;
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
