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
     * @param array  $options
     *
     * @return ReaderFactory
     */
    public function addReader($className, array $options = [])
    {
        if (is_array($className)) {
            $options   = $className;
            $className = null;
        }
        $this->readers[] = [
            'className' => $className,
            'create'    => isset($options['create']) && is_callable($options['create']) ? $options['create'] : null,
            'accepts'   => isset($options['accepts']) && is_callable($options['accepts']) ? $options['accepts'] : null
        ];

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
            if ((isset($reader['accepts']) && call_user_func($reader['accepts'], $input))
                || (method_exists($reader['className'], 'accepts')
                    && call_user_func([$reader['className'], 'accepts'], $input))) {
                if ($reader['create']) {
                    return call_user_func($reader['create'], $input);
                } else {
                    return $this->createInstance($reader['className'], $input);
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
