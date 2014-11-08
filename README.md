<img src="https://florian.ec/img/plum/logo.png" alt="Plum">
====

> Plum is a data processing pipeline that helps you to write structured, reusable and well tested data processing code.

[![Build Status](https://travis-ci.org/florianeckerstorfer/plum.svg?branch=master)](https://travis-ci.org/florianeckerstorfer/plum)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/florianeckerstorfer/plum/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/florianeckerstorfer/plum/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/florianeckerstorfer/plum/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/florianeckerstorfer/plum/?branch=master)

Developed by [Florian Eckerstorfer](https://florian.ec) in Vienna, Europe.


Features
--------

Plum is a data processing pipeline, that means it reads data, filters and converts it and then writes the data.

- Filters, converters and even writers are pipeline elements that can be attached to a workflow in arbitrary order
- Readers are iterators that can return values of arbitrary type: arrays, objects or scalars, it doesn't matter to Plum
- Conditional converters that are only applied to an item if it passes a filter

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
use Cocur\Plum\Workflow;

$workflow = new Workflow();
$workflow->addFilter($filter)
         ->addConverter($converter)
         ->addWriter($writer);
$workflow->process($reader);
```

#### Conditional Converters

The `addConverter()` method accepts an optional second parameter of type `Cocur\Plum\Filter\FilterInterface`. If a
filter is provided the converter is only applied to an item if the filter returns `true` for the given item. Otherwise
the original item is returned by the converter.

```php
$converter = new CallbackConverter(function ($item) { return strtoupper($item); });
$filter    = new CallbackFilter(function ($item) { return preg_match('/foo/', $item); });
$workflow->addConverter($converter, $filter);

// "foobar" -> "FOOBAR"
// "bazbar" -> "bazbar"
```

#### Result

The `process()` method returns an instance of `Cocur\Plum\Result`. This object contains information and errors
collected during the processing. 

```php
$result = $workflow->process($reader);
$result->getReadCount(); // -> int
$result->getWriteCount(); // -> int
$result->getErrorCount(); // -> int
$result->getExceptions(); // -> \Exception[]
```

### Readers

You read data using an object that implements `ReaderInterface`. This interface extends `\Iterator` interface and
therefore you need to implement its methods. The return value of the `current()` method can be of an arbitrary type,
arrays, objects or scalar values, Plum doesn't care.

#### ArrayReader

The `ArrayReader` feeds the elements of an array to the workflow. In addition to the methods required by 
`ReaderInterface` it provides a `getData()` methods that returns the full array.

```php
use Cocur\Plum\Reader\ArrayReader;

$reader = new ArrayReader(['Stark', 'Lannister', 'Targaryen', ...]);
$reader->getData(); // -> ['Stark', 'Lannister', 'Targaryen', ...]
```

#### FinderReader

You can read directories and files using the Symfony Finder component and `FinderReader`.

```php
use Cocur\Plum\Reader\FinderReader;
use Symfony\Component\Finder\Finder;

$finder = new Finder();
// Further configuration of Finder

$reader = new FinderReader($finder);
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
use Cocur\Plum\Writer\ArrayWriter;

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
use Cocur\Plum\Filter\CallbackFilter;

$filter = new CallbackFilter(function ($item) {
    return preg_match('/https?:\/\/[a-z0-9-]+\.[a-z]+/', $item);
});
$filter->filter('https://florian.ec'); // -> true
```

#### FileExtensionFilter

Checks if the file extension of a file name matches.

```
use Cocur\Plum\Filter\FileExtensionFilter;

$filter = new FileExtensionFilter('md');
$filter->filter('README.md'); // -> true
$filter->filter('README.html'); // -> false
```

### Converters

Converters take an item and convert it into something else. They must implement `ConverterInterface` which has a single
`convert()` method.

#### CallbackConverter

The `CallbackConverter` calls a callback to convert a given item.

```php
use Cocur\Plum\Converter\CallbackConverter;

$converter = new CallbackConverter(function ($item) { return strtoupper($item); });
$converter->convert('foo'); // -> FOO
```

#### FileGetContentsConverter

The `FileGetContentsConverter` takes a `SplFileInfo` object or a filename and returns both the `SplFileInfo` object
and the contents of the file.

```php
use Cocur\Plum\Converter\FileGetContentsConverter;

$converter = new FileGetContentsConverter();
$converter->convert('foo.txt'); // -> ['file' => \SplFileInfo Object, 'content' => '...'] 
```


Change Log
----------

*no version released yet*


License
-------

The MIT license applies to florianeckerstorfer/plum. For the full copyright and license information,
please view the LICENSE file distributed with this source code.
