<img src="https://florian.ec/img/plum/logo.png" alt="Plum">
====

> Plum is a data processing pipeline that helps you to write structured, reusable and well tested data processing code.

[![Build Status](https://travis-ci.org/florianeckerstorfer/plum.svg?branch=master)](https://travis-ci.org/florianeckerstorfer/plum)
[![Test Coverage](https://codeclimate.com/github/florianeckerstorfer/plum/badges/coverage.svg)](https://codeclimate.com/github/florianeckerstorfer/plum)
[![Code Climate](https://codeclimate.com/github/florianeckerstorfer/plum/badges/gpa.svg)](https://codeclimate.com/github/florianeckerstorfer/plum)

Developed by [Florian Eckerstorfer](https://florian.ec) in Vienna, Europe.


Features
--------

Plum is a data processing pipeline, that means it reads data, filters and converts it and then writes the data.

- Filters, converters and even writers are pipeline elements that can be attached to a workflow in arbitrary order
- Readers are iterators that can return values of arbitrary type: arrays, objects or scalars, it doesn't matter to Plum

*Plum has been greatly inspired by [ddeboer/data-import](https://github.com/ddeboer/data-import).*


Installation
------------

You can install Plum using [Composer](http://getcomposer.org).

```shell
$ composer require florianeckerstorfer/plum:dev-master
```


Usage
-----

You configure and compose your data processing pipeline with a `Workflow` object; attach filters, converters and
writers to the workflow and process it using the `process()` method.

*Working examples can be found in the examples/ directory.*


### Workflow

```php
use FlorianEc\Plum\Workflow;

$workflow = new Workflow();
$workflow->addFilter($filter)
         ->addConverter($converter)
         ->addWriter($writer);
$workflow->process($reader);
```

### Readers

You read data using an object that implements `ReaderInterface`. This interface extends `\Iterator` interface and
therefore you need to implement its methods. The return value of the `current()` method can be of an arbitrary type,
arrays, objects or scalar values, Plum doesn't care.

#### ArrayReader

The `ArrayReader` feeds the elements of an array to the workflow. In addition to the methods required by 
`ReaderInterface` it provides a `getData()` methods that returns the full array.

```php
use FlorianEc\Plum\Reader\ArrayReader;

$reader = new ArrayReader(['Stark', 'Lannister', 'Targaryen', ...]);
$reader->getData(); // -> ['Stark', 'Lannister', 'Targaryen', ...]
```

### Writers

Use writers to write the result of the workflow. The target doesn't necessarily have to write to a persistent storage,
you can also write, for example, into arrays or objects. Writers must implement the `WriterInterface` that provides
three methods: `writeItem()`, `prepare()` and `finish()`. The workflow calls `prepare()` before it reads the first item
and `finish()` after it read the last item.

Multiple writers can be added to a workflow in any ordering. Therefore it is possible to filter the read items, write
them somewhere, further filter them and then write them elsewhere. 

#### ArrayWriter

The `ArrayWriter` writes the data into an array that can be retrieved using the `getData()` method.

```php
use FlorianEc\Plum\Writer\ArrayWriter;

$writer = new ArrayWriter();
// Workflow processing
$writer->getData() // -> [...]
```

### Filters

You use filters to remove items from the pipeline. Filters implement `FilterInterface` and must provide a `filter()`
method. Every item of the pipeline is passed to the filter and if the return value evaluates to `false` (`false`,
`null`, `0`, ...) the item is no further processed.

#### CallbackFilter

The `CallbackFilter` calls a callback.

```php
use FlorianEc\Plum\Filter\CallbackFilter;

$filter = new CallbackFilter(function ($item) {
    return preg_match('/https?:\/\/[a-z0-9-]+\.[a-z]+/', $item);
});
$filter->filter('https://florian.ec'); // -> true
```

### Converters

Converters take an item and convert it into something else. They must implement `ConverterInterface` which has a single
`convert()` method.

#### CallbackConverter

The `CallbackConverter` calls a callback to convert a given item.

```php
use FlorianEc\Plum\Converter\CallbackConverter;

$converter = new CallbackConverter(function ($item) { return strtoupper($item); });
$converter->convert('foo'); // -> FOO
```


Change Log
----------

*no version released yet*


License
-------

