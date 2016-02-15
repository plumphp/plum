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

use LogicException;

/**
 * SkipFirstFilter.
 *
 * @author    Sebastian Göttschkes <sebastian.goettschkes@googlemail.com>
 * @copyright 2014-2016 Florian Eckerstorfer
 */
class SkipFirstFilter implements FilterInterface
{
    /** @var int */
    private $counter;

    public function __construct($counter)
    {
        if (!is_int($counter)) {
            throw new LogicException('SkipFirstFilter expects an integer as first argument');
        }

        $this->counter = $counter;
    }

    /**
     * @param mixed $item
     *
     * @return bool
     */
    public function filter($item)
    {
        if ($this->counter > 0) {
            --$this->counter;

            return false;
        }

        return true;
    }
}
