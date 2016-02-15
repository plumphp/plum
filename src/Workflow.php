<?php

/**
 * This file is part of plumphp/plum.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plum\Plum;

use Cocur\Vale\Vale;
use InvalidArgumentException;
use Plum\Plum\Converter\ConverterInterface;
use Plum\Plum\Filter\FilterInterface;
use Plum\Plum\Pipe\ConverterPipe;
use Plum\Plum\Pipe\FilterPipe;
use Plum\Plum\Pipe\AbstractPipe;
use Plum\Plum\Pipe\WriterPipe;
use Plum\Plum\Reader\ReaderInterface;
use Plum\Plum\Writer\WriterInterface;

/**
 * Workflow.
 *
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014-2016 Florian Eckerstorfer
 */
class Workflow
{
    const APPEND  = 1;
    const PREPEND = 2;

    /**
     * @var AbstractPipe[]
     */
    private $pipeline = [];

    /**
     * @var array
     */
    private $options = [
        'resumeOnError' => false,
    ];

    /**
     * @param array $options
     *
     * @codeCoverageIgnore
     */
    public function __construct(array $options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * @param string|null $type
     *
     * @return AbstractPipe[]
     */
    public function getPipeline($type = null)
    {
        $pipeline = [];

        foreach ($this->pipeline as $element) {
            if ($type === null || get_class($element) === $type) {
                $pipeline[] = $element;
            }
        }

        return $pipeline;
    }

    /**
     * Inserts an element into the pipeline at the given position.
     *
     * @param AbstractPipe $pipe
     *
     * @return Workflow
     */
    protected function addPipe(AbstractPipe $pipe)
    {
        if ($pipe->getPosition() === self::PREPEND) {
            array_unshift($this->pipeline, $pipe);
        } else {
            $this->pipeline[] = $pipe;
        }

        return $this;
    }

    /**
     * @param array|callable|FilterInterface $element
     *
     * @return Workflow
     *
     * @throws InvalidArgumentException
     */
    public function addFilter($element)
    {
        $pipe = FilterPipe::createFilter($element);

        return $this->addPipe($pipe);
    }

    /**
     * @return FilterPipe[]
     */
    public function getFilters()
    {
        return $this->getPipeline('Plum\Plum\Pipe\FilterPipe');
    }

    /**
     * @param ConverterInterface|callable|array $element
     *
     * @return Workflow $this
     */
    public function addConverter($element)
    {
        $pipe = ConverterPipe::createConverter($element);

        return $this->addPipe($pipe);
    }

    /**
     * @return ConverterPipe[]
     */
    public function getConverters()
    {
        return $this->getPipeline('Plum\Plum\Pipe\ConverterPipe');
    }

    /**
     * @param WriterInterface|array $element
     *
     * @return Workflow
     */
    public function addWriter($element)
    {
        $pipe = WriterPipe::createWriter($element);

        return $this->addPipe($pipe);
    }

    /**
     * @return WriterPipe[]
     */
    public function getWriters()
    {
        return $this->getPipeline('Plum\Plum\Pipe\WriterPipe');
    }

    /**
     * @param ReaderInterface[]|ReaderInterface $readers
     *
     * @return Result
     */
    public function process($readers)
    {
        if (!is_array($readers)) {
            $readers = [$readers];
        }

        $result = new Result();

        $this->prepareWriters($this->getWriters());

        foreach ($readers as $reader) {
            $this->processReader($reader, $result);
        }

        $this->finishWriters($this->getWriters());

        return $result;
    }

    /**
     * @param ReaderInterface $reader
     * @param Result          $result
     */
    protected function processReader(ReaderInterface $reader, Result $result)
    {
        foreach ($reader as $item) {
            $result->incReadCount();
            try {
                $this->processItem($item, $result);
            } catch (\Exception $e) {
                $result->addException($e);
                if (!$this->options['resumeOnError']) {
                    throw $e;
                }
            }
        }
    }

    /**
     * @param WriterPipe[] $writers
     */
    protected function prepareWriters($writers)
    {
        foreach ($writers as $element) {
            $element->getWriter()->prepare();
        }
    }

    /**
     * @param WriterPipe[] $writers
     */
    protected function finishWriters($writers)
    {
        foreach ($writers as $element) {
            $element->getWriter()->finish();
        }
    }

    /**
     * @param mixed  $item
     * @param Result $result
     */
    protected function processItem($item, Result $result)
    {
        $written = false;

        foreach ($this->pipeline as $element) {
            if ($element instanceof FilterPipe && $element->getField()) {
                if ($element->getFilter()->filter(Vale::get($item, $element->getField())) === false) {
                    return;
                }
            } elseif ($element instanceof FilterPipe) {
                if ($element->getFilter()->filter($item) === false) {
                    return;
                }
            } elseif ($element instanceof ConverterPipe && $element->getField()) {
                $item = $this->convertItemValue($item, $element);
            } elseif ($element instanceof ConverterPipe) {
                $item = $this->convertItem($item, $element);
                if ($item === null) {
                    return;
                }
            } elseif ($element instanceof WriterPipe) {
                if ($this->writeItem($item, $element) === true) {
                    $result->incWriteCount();
                    $written = true;
                }
            }
        }

        if ($written === true) {
            $result->incItemWriteCount();
        }
    }

    /**
     * Applies the given converter to the given item either if no filter is given or if the filter returns `true`.
     *
     * @param mixed         $item
     * @param ConverterPipe $pipe
     *
     * @return mixed
     */
    protected function convertItem($item, ConverterPipe $pipe)
    {
        $filterValue = $pipe->getFilterField() ? Vale::get($item, $pipe->getFilterField()) : $item;
        if ($pipe->getFilter() === null || $pipe->getFilter()->filter($filterValue) === true) {
            return $pipe->getConverter()->convert($item);
        }

        return $item;
    }

    /**
     * Applies the given converter to the given field in the given item if no filter is given or if the filters returns
     * `true` for the field.
     *
     * @param mixed         $item
     * @param ConverterPipe $pipe
     *
     * @return mixed
     */
    protected function convertItemValue($item, ConverterPipe $pipe)
    {
        $value       = Vale::get($item, $pipe->getField());
        $filterValue = $pipe->getFilterField() ? Vale::get($item, $pipe->getFilterField()) : $item;

        if ($pipe->getFilter() === null || $pipe->getFilter()->filter($filterValue) === true) {
            $item = Vale::set($item, $pipe->getField(), $pipe->getConverter()->convert($value));
        }

        return $item;
    }

    /**
     * Writes the given item to the given writer if the no filter is given or the filter returns `true`.
     *
     * @param mixed      $item
     * @param WriterPipe $pipe
     *
     * @return bool `true` if the item has been written, `false` if not.
     */
    protected function writeItem($item, WriterPipe $pipe)
    {
        if ($pipe->getFilter() === null || $pipe->getFilter()->filter($item) === true) {
            $pipe->getWriter()->writeItem($item);

            return true;
        }

        return false;
    }
}
