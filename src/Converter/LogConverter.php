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

use Psr\Log\LoggerInterface;

/**
 * LogConverter.
 *
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2015 Florian Eckerstorfer
 */
class LogConverter implements ConverterInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var string
     */
    protected $level;

    /**
     * @var string
     */
    protected $message = 'Converting item';

    /**
     * @param LoggerInterface $logger
     * @param string          $level
     * @param string|null     $message
     */
    public function __construct(LoggerInterface $logger, $level = 'debug', $message = null)
    {
        $this->logger = $logger;
        $this->level  = $level;
        if ($message !== null) {
            $this->message = $message;
        }
    }

    /**
     * @param mixed $item
     *
     * @return mixed
     */
    public function convert($item)
    {
        $this->logger->log($this->level, $this->message, $item);

        return $item;
    }
}
