<?php

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
    const CONVERTER_INTERFACE = 'FlorianEc\Plum\Converter\ConverterInterface';
    const FILTER_INTERFACE    = 'FlorianEc\Plum\Filter\FilterInterface';
    const WRITER_INTERFACE    = 'FlorianEc\Plum\Writer\WriterInterface';

    /** @var PipelineInterface[] */
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
            if (!$type || is_a($element, $type)) {
                $pipeline[] = $element;
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
        $this->pipeline[] = $filter;

        return $this;
    }

    /**
     * @return FilterInterface[]
     */
    public function getFilters()
    {
        return $this->getPipeline(self::FILTER_INTERFACE);
    }

    /**
     * @param ConverterInterface $converter
     *
     * @return Workflow $this
     */
    public function addConverter(ConverterInterface $converter)
    {
        $this->pipeline[] = $converter;

        return $this;
    }

    /**
     * @return ConverterInterface[]
     */
    public function getConverters()
    {
        return $this->getPipeline(self::CONVERTER_INTERFACE);
    }

    /**
     * @param WriterInterface $writer
     *
     * @return Workflow
     */
    public function addWriter(WriterInterface $writer)
    {
        $this->pipeline[] = $writer;

        return $this;
    }

    /**
     * @return WriterInterface[]
     */
    public function getWriters()
    {
        return $this->getPipeline(self::WRITER_INTERFACE);
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
            if (is_a($element, self::FILTER_INTERFACE)) {
                if (!$element->filter($item)) {
                    return;
                }
            } else if (is_a($element, self::CONVERTER_INTERFACE)) {
                $item = $element->convert($item);
            } else if (is_a($element, self::WRITER_INTERFACE)) {
                $element->writeItem($item);
            }
        }
    }
}
