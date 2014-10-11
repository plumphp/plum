<?php

/**
 * This file is part of florianeckerstorfer/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FlorianEc\Plum\Writer;

/**
 * WriterInterface
 *
 * @package   FlorianEc\Plum\Writer
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 */
interface WriterInterface
{
    /**
     * Write the given item.
     *
     * @param mixed $item
     */
    public function writeItem($item);

    /**
     * Prepare the writer.
     */
    public function prepare();

    /**
     * Finish the writer.
     */
    public function finish();
}
