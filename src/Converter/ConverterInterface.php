<?php

/**
 * This file is part of plumphp/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plum\Plum\Converter;

use Plum\Plum\PipelineInterface;

/**
 * ConverterInterface.
 *
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014-2016 Florian Eckerstorfer
 */
interface ConverterInterface extends PipelineInterface
{
    /**
     * @param mixed $item
     *
     * @return mixed
     */
    public function convert($item);
}
