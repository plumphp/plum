<?php

/**
 * This file is part of florianeckerstorfer/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
