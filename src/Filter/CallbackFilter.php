<?php

/**
 * This file is part of florianeckerstorfer/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocur\Plum\Filter;

/**
 * CallbackFilter
 *
 * @package   Cocur\Plum\Filter
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 */
class CallbackFilter implements FilterInterface
{
    /** @var callable */
    private $callback;

    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param mixed $item
     *
     * @return bool
     */
    public function filter($item)
    {
        return call_user_func($this->callback, $item);
    }
}
