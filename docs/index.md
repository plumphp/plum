<p align="center">
    <img src="http://cdn.florian.ec/plum-logo.svg" alt="Plum" width="300">
</p>

> Plum is a data processing pipeline that helps you to write structured, reusable and well tested data processing code.

Developed by [Florian Eckerstorfer](https://florian.ec) in Vienna, Europe.

Features
--------

Plum is a data processing pipeline, that means it reads data, filters and converts it and then writes the data.

- Plum provides you with readers and writers for common data formats as well as converters and filters that are 
frequently required in data processing tasks.
- Filters, converters and even writers are pipeline elements that can be attached to a workflow in arbitrary order
- Readers are iterators that can return values of arbitrary type: arrays, objects or scalars, it doesn't matter to Plum
- Conditional converters that are only applied to an item if it passes a filter
- Ability to concatenate workflow to create smaller and better reusable workflows
- Read from multiple sources, i.e., merge data from different sources into an output
- By providing you with clean interfaces and a strong separation of concern your data processing code will be more
structured, better reusable and well tested.
- Plums power comes from its extendability, check out [additional packages and integrations](docs/extensions.md)

*Plum has been greatly inspired by [ddeboer/data-import](https://github.com/ddeboer/data-import).*


Installation
------------

You can install Plum using [Composer](http://getcomposer.org) (recommended) or by downloading a
[release](https://github.com/plumphp/plum/releases).

```shell
$ composer require plumphp/plum
```


Workflow
--------

Plum provides you with a `Workflow` class and you can attach filters, converters and writers to it and process it
using one or multiple readers. A workflow can be processed one time or multiple times.

```php
use Plum\Plum\Workflow;

$workflow = new Workflow();
$workflow->addFilter($filter)
         ->addConverter($converter)
         ->addWriter($writer);
$result = $workflow->process($reader);
```


Example
-------

To give you a quick example of the power of Plum here is a simple *CSV to Excel* converter.
 
```php
use Plum\Plum\Workflow;
use Plum\Plum\Converter\HeaderConverter;
use Plum\Plum\Filter\SkipFirstFilter;
use Plum\PlumCsv\CsvReader;
use Plum\PlumExcel\ExcelWriter;

$workflow = new Workflow();
$workflow->addConverter(new HeaderConverter())
         ->addFilter(new SkipFirstFilter(1))
         ->addWriter((new ExcelWriter('output/countries.xlsx'))->autoDetectHeader());
$result = $workflow->process(new CsvReader('input/countries.csv'));
```


Table of Contents
-----------------

1. [Workflow](workflow.md)
2. [Readers](readers.md)
3. [Writers](writers.md)
4. [Filters](filters.md)
5. [Converters](converters.md)
6. [Extensions](extensions.md)

---

<p align="center">
    <strong>Index</strong>
    <a href="workflow.md">Workflow</a>
    <a href="readers.md">Readers</a>
    <a href="writers.md">Writers</a>
    <a href="filters.md">Filters</a>
    <a href="converters.md">Converters</a>
    <a href="extensions.md">Extensions</a>
</p>
