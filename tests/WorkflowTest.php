<?php

namespace FlorianEc\Plum;

use \Mockery as m;

/**
 * WorkflowTest
 *
 * @package   FlorianEc\Plum
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 *
 * @group unit
 */
class WorkflowTest extends \PHPUnit_Framework_TestCase
{
    /** @var Workflow */
    private $workflow;

    public function setUp()
    {
        $this->workflow = new Workflow();
    }
    
    /**
     * @test
     * @covers FlorianEc\Plum\Workflow::getPipeline()
     */
    public function getPipelineShouldReturnAllPipelineElements()
    {
        /** @var \FlorianEc\Plum\Filter\FilterInterface $filter */
        $filter = m::mock('FlorianEc\Plum\Filter\FilterInterface');
        /** @var \FlorianEc\Plum\Converter\ConverterInterface $converter */
        $converter = m::mock('FlorianEc\Plum\Converter\ConverterInterface');

        $this->workflow->addFilter($filter);
        $this->workflow->addConverter($converter);

        $this->assertCount(2, $this->workflow->getPipeline());
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Workflow::getPipeline()
     */
    public function getPipelineShouldReturnOnlyElementsOfGivenType()
    {
        /** @var \FlorianEc\Plum\Filter\FilterInterface $filter */
        $filter = m::mock('FlorianEc\Plum\Filter\FilterInterface');
        /** @var \FlorianEc\Plum\Converter\ConverterInterface $converter */
        $converter = m::mock('FlorianEc\Plum\Converter\ConverterInterface');

        $this->workflow->addFilter($filter);
        $this->workflow->addConverter($converter);

        $this->assertCount(1, $this->workflow->getPipeline('FlorianEc\Plum\Filter\FilterInterface'));
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Workflow::addFilter()
     * @covers FlorianEc\Plum\Workflow::getFilters()
     */
    public function addFilterShouldAddFilterToWorkflow()
    {
        /** @var \FlorianEc\Plum\Filter\FilterInterface $filter */
        $filter = m::mock('FlorianEc\Plum\Filter\FilterInterface');
        $this->workflow->addFilter($filter);

        $this->assertContains($filter, $this->workflow->getFilters());
    }

    /**
     * @test
     * @covers FlorianEc\Plum\Workflow::addConverter()
     * @covers FlorianEc\Plum\Workflow::getConverters()
     */
    public function addConverterShouldAddConverterToWorkflow()
    {
        /** @var \FlorianEc\Plum\Converter\ConverterInterface $converter */
        $converter = m::mock('FlorianEc\Plum\Converter\ConverterInterface');
        $this->workflow->addConverter($converter);

        $this->assertContains($converter, $this->workflow->getConverters());
    }
}
 