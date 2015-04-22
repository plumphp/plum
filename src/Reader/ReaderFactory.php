<?php

/**
 * This file is part of plumphp/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plum\Plum\Reader;

/**
 * ReaderFactory
 *
 * @package   Plum\Plum\Reader
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2015 Florian Eckerstorfer
 */
class ReaderFactory
{
    /**
     * @var string[]
     */
    protected $readers = [];

    /**
     * @param string $className
     *
     * @return ReaderFactory
     */
    public function addReader($className, callable $createFunction = null)
    {
        $this->readers[] = ['className' => $className, 'createFunction' => $createFunction];

        return $this;
    }

    /**
     * @param mixed $input
     *
     * @return ReaderInterface|null
     */
    public function create($input)
    {
        foreach ($this->readers as $reader) {
            if (call_user_func([$reader['className'], 'accepts'], $input)) {
                if (!$reader['createFunction']) {
                    return $this->createInstance($reader['className'], $input);
                } else {
                    return call_user_func($reader['createFunction'], $input);
                }
            }
        }

        return null;
    }

    /**
     * @param string $className
     * @param mixed  $input
     *
     * @return ReaderInterface
     */
    protected function createInstance($className, $input)
    {
        $obj = new $className($input);

        if (!$obj instanceof ReaderInterface) {
            throw new \RuntimeException(sprintf(
                'The given reader class "%s" does not implement "Plum\Plum\Reader\ReaderInterface".',
                $className
            ));
        }

        return $obj;
    }
}
