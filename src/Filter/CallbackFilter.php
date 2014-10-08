<?php

namespace FlorianEc\Plum\Filter;

/**
 * CallbackFilter
 *
 * @package   FlorianEc\Plum\Filter
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
