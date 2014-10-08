<?php

namespace FlorianEc\Plum;
use FlorianEc\Plum\Converter\ConverterInterface;
use FlorianEc\Plum\Filter\FilterInterface;

/**
 * Workflow
 *
 * @package   FlorianEc\Plum
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 */
class Workflow
{
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
        return $this->getPipeline('FlorianEc\Plum\Filter\FilterInterface');
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
        return $this->getPipeline('FlorianEc\Plum\Converter\ConverterInterface');
    }
}
