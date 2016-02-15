<?php

/**
 * This file is part of plumphp/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plum\Plum\Filter;

use Plum\Plum\PipelineInterface;

/**
 * FilterInterface.
 *
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014-2016 Florian Eckerstorfer
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
