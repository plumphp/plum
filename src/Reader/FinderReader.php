<?php

/**
 * This file is part of florianeckerstorfer/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocur\Plum\Reader;

use Symfony\Component\Finder\Finder;
use Traversable;

/**
 * FinderReader
 *
 * @package   Cocur\Plum\Reader
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 */
class FinderReader implements ReaderInterface
{
    /** @var Finder */
    private $finder;

    /**
     * @param Finder $finder
     *
     * @codeCoverageIgnore
     */
    public function __construct(Finder $finder)
    {
        $this->finder = $finder;
    }

    /**
     * @return \Iterator
     */
    public function getIterator()
    {
        return $this->finder->getIterator();
    }

    /**
     * Returns the number of files that are read.
     *
     * @return int
     */
    public function count()
    {
        return $this->finder->count();
    }
}
