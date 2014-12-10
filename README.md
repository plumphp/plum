<img src="https://florian.ec/img/plum/logo.png" alt="Plum">
====

> Plum is a data processing pipeline that helps you to write structured, reusable and well tested data processing code.

[![Build Status](https://travis-ci.org/cocur/plum.svg?branch=master)](https://travis-ci.org/cocur/plum)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/cocur/plum/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/cocur/plum/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/cocur/plum/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/cocur/plum/?branch=master)

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
$ composer require cocur/plum:dev-master
```


Usage
-----

Here is a quick preview, but please check out the [documentation](http://plum.readthedocs.org/en/latest/).

```php
use Cocur\Plum\Workflow;

$workflow = new Workflow();
$workflow->addFilter($filter)
         ->addConverter($converter)
         ->addWriter($writer);
$workflow->process($reader);
```


Change Log
----------

### Version 0.1 (10 December 2014)

- Initial version


License
-------

The MIT license applies to cocur/plum. For the full copyright and license information,
please view the LICENSE file distributed with this source code.
