<?php

/**
 * This file is part of cocur/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocur\Plum\Converter;

/**
 * FileGetContentsConverter
 *
 * @package   Cocur\Plum\Converter
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 */
class FileGetContentsConverter implements ConverterInterface
{
    /**
     * @param mixed $item
     *
     * @return array<\SplFileInfo, string>
     */
    public function convert($item)
    {
        if ($item instanceof \SplFileInfo === false) {
            $item = new \SplFileInfo($item);
        }
        if ($item->isReadable() === false) {
            throw new \InvalidArgumentException(sprintf('The given file "%s" is not readable.', $item->getPathname()));
        }

        return [
            'file' => $item,
            'content' => file_get_contents($item->getPathname())
        ];
    }
}
