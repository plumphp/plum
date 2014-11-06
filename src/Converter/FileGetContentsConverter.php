<?php

/**
 * This file is part of florianeckerstorfer/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FlorianEc\Plum\Converter;

/**
 * FileGetContentsConverter
 *
 * @package   FlorianEc\Plum\Converter
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 */
class FileGetContentsConverter implements ConverterInterface
{
    /**
     * @param mixed $item
     *
     * @return mixed
     */
    public function convert($item)
    {
        if (!$item instanceof \SplFileInfo) {
            $item = new \SplFileInfo($item);
        }
        if (!$item->isReadable()) {
            throw new \InvalidArgumentException(sprintf('The given file "%s" is not readable.', $item->getPathname()));
        }

        return [
            'file'    => $item,
            'content' => file_get_contents($item->getPathname())
        ];
    }
}
