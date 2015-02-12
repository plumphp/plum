<img src="https://florian.ec/img/plum/logo.png" alt="Plum">
===

Plum is a data processing pipeline that helps you to write structured, reusable and well tested data processing code.

[![Build Status](https://travis-ci.org/plumphp/plum.svg?branch=master)](https://travis-ci.org/plumphp/plum)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/plumphp/plum/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/plumphp/plum/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/plumphp/plum/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/plumphp/plum/?branch=master)

Developed by [Florian Eckerstorfer](https://florian.ec) in Vienna, Europe.


Features
--------

Plum is a data processing pipeline, that means it reads data, filters and converts it and then writes the data.

- Filters, converters and even writers are pipeline elements that can be attached to a workflow in arbitrary order
- Readers are iterators that can return values of arbitrary type: arrays, objects or scalars, it doesn't matter to Plum
- Conditional converters that are only applied to an item if it passes a filter
- Ability to concatenate workflow to create smaller and better reusable workflows

*Plum has been greatly inspired by [ddeboer/data-import](https://github.com/ddeboer/data-import).*


Installation
------------

You can install Plum using [Composer](http://getcomposer.org).

```shell
$ composer require plumphp/plum:@stable
```

*Tip:* Replace `@stable` with a version from the [releases page](https://github.com/plumphp/plum/releases).


Usage
-----

You configure and compose your data processing pipeline with a `Workflow` object; attach filters, converters and
writers to the workflow and process it using the `process()` method.

*Working examples can be found in the examples/ directory.*

```php
use Plum\Plum\Workflow;

$workflow = new Workflow();
$workflow->addFilter($filter)
         ->addConverter($converter)
         ->addWriter($writer);
$workflow->process($reader);
```

Further documentation:

- [Workflow](workflow.md)
- [Readers](readers.md)
- [Writers](writers.md)
- [Filters](filters.md)
- [Converters](converters.md)

The base `plum` package contains the base classes and interfaces. Specific readers, writers, filter and converters
can be added as separate packages. A list of [Plum packages](https://github.com/plumphp) can be found on Github.
