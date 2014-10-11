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
            if (!$type || $element[0] === $type) {
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
     * @param ConverterInterface $converter
     *
     * @return Workflow $this
     */
    public function addConverter(ConverterInterface $converter)
    {
        $this->pipeline[] = [self::PIPELINE_TYPE_CONVERTER, $converter];

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
     */
    public function process(ReaderInterface $reader)
    {
        foreach ($this->getWriters() as $writer) {
            $writer->prepare();
        }

        foreach ($reader as $item) {
            $this->processItem($item);
        }

        foreach ($this->getWriters() as $writer) {
            $writer->finish();
        }
    }

    /**
     * @param $item
     */
    protected function processItem($item)
    {
        foreach ($this->pipeline as $element) {
            if ($element[0] === self::PIPELINE_TYPE_FILTER) {
                if (!$element[1]->filter($item)) {
                    return;
                }
            } else if ($element[0] === self::PIPELINE_TYPE_CONVERTER) {
                $item = $element[1]->convert($item);
            } else if ($element[0] === self::PIPELINE_TYPE_WRITER) {
                $element[1]->writeItem($item);
            }
        }
    }
}
