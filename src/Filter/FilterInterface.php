<?php

namespace FlorianEc\Plum\Filter;

use FlorianEc\Plum\PipelineInterface;

/**
 * FilterInterface
 *
 * @package   FlorianEc\Plum\Filter
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 */
interface FilterInterface extends PipelineInterface
{
    /**
     * @param mixed $item
     *
     * @return bool
     */
    public function filter($item);
}
