<?php

/**
 * This file is part of florianeckerstorfer/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FlorianEc\Plum\Converter;

use FlorianEc\Plum\PipelineInterface;

/**
 * ConverterInterface
 *
 * @package   FlorianEc\Plum\Converter
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
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
