<img src="https://florian.ec/img/plum/logo.png" alt="Plum">
====

> Plum is a data processing pipeline that helps you to write structured, reusable and well tested data processing code.

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
$ composer require plumphp/plum:dev-master
```


Usage
-----

Here is a quick preview, but please check out the 
[documentation](https://github.com/plumphp/plum/blob/master/docs/index.md).

```php
use Plum\Plum\Workflow;

$workflow = new Workflow();
$workflow->addFilter($filter)
         ->addConverter($converter)
         ->addWriter($writer);
$workflow->process($reader);
```


Additional packages
-------------------

The core of Plum (the `plumphp/plum` package) contains only the essential classes. However, we provide additional
packages which give you more functionality out of the box:

- [plum-csv](https://github.com/plumphp/plum-csv) Readers and writers for CSV files.
- [plum-json](https://github.com/plumphp/plum-json) Readers and writers for JSON strings and files.
- [plum-finder](https://github.com/plumphp/plum-finder) Integration for the Symfony Finder component.
- [plum-console](https://github.com/plumphp/plum-console) Integration for the Symfony Console component.


Change Log
----------

*No version released.*


License
-------

The MIT license applies to plumphp/plum. For the full copyright and license information,
please view the LICENSE file distributed with this source code.
