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
     * @param string $reader
     *
     * @return ReaderFactory
     */
    public function addReader($reader)
    {
        $this->readers[] = $reader;

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
            if (call_user_func([$reader, 'accepts'], $input)) {
                return $this->createInstance($reader, $input);
            }
        }

        return null;
    }

    /**
     * @param $reader
     * @param $input
     *
     * @return ReaderInterface
     */
    protected function createInstance($reader, $input)
    {
        $obj = new $reader($input);

        if (!$obj instanceof ReaderInterface) {
            throw new \RuntimeException(sprintf(
                'The given reader class "%s" does not implement "Plum\Plum\Reader\ReaderInterface".',
                $reader
            ));
        }

        return $obj;
    }
}
