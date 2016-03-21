<?php

/**
 * This file is part of plumphp/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Plum\Plum\Converter;

/**
 * NumberFormatConverter.
 *
 * @author    Florian Eckerstorfer
 * @copyright 2014-2016 Florian Eckerstorfer
 */
class NumberFormatConverter implements ConverterInterface
{
    /**
     * @var int
     */
    protected $decimals;

    /**
     * @var string
     */
    protected $decimalPoint;

    /**
     * @var string
     */
    protected $thousandsSeparator;

    /**
     * NumberFormatConverter constructor.
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandsSeparator
     *
     * @codeCoverageIgnore
     */
    public function __construct($decimals = 0, $decimalPoint = '.', $thousandsSeparator = ',')
    {
        $this->decimals           = $decimals;
        $this->decimalPoint       = $decimalPoint;
        $this->thousandsSeparator = $thousandsSeparator;
    }

    /**
     * @param mixed $item
     *
     * @return mixed
     */
    public function convert($item)
    {
        return number_format($item, $this->decimals, $this->decimalPoint, $this->thousandsSeparator);
    }
}
