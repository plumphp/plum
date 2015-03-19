<?php

/**
 * This file is part of plumphp/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plum\Plum\Writer;

use Plum\Plum\PipelineInterface;

/**
 * WriterInterface
 *
 * @package   Plum\Plum\Writer
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014-2015 Florian Eckerstorfer
 */
interface WriterInterface extends PipelineInterface
{
    /**
     * Write the given item.
     *
     * @param mixed $item
     *
     * @return void
     */
    public function writeItem($item);

    /**
     * Prepare the writer.
     *
     * @return void
     */
    public function prepare();

    /**
     * Finish the writer.
     *
     * @return void
     */
    public function finish();
}
