<p align="center">
    <img src="http://cdn.florian.ec/plum-logo.svg" alt="Plum" width="300">
</p>

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
- Read from multiple sources, i.e., merge data from different sources into an output
- Plums power comes from its extendability, check out [additional packages and integrations](docs/extensions.md)

*Plum has been greatly inspired by [ddeboer/data-import](https://github.com/ddeboer/data-import).*


Installation
------------

You can install Plum using [Composer](http://getcomposer.org) (recommended) or by downloading a
[release](https://github.com/plumphp/plum/releases).

```shell
$ composer require plumphp/plum
```


Usage
-----

Here is a quick preview, but please check out the
[documentation](https://github.com/plumphp/plum/blob/master/docs/index.md).

```php
use Plum\Plum\Workflow;

$workflow = new Workflow();
$workflow->addFilter(/* filter */)
         ->addConverter(/* converter */)
         ->addWriter(/* writer */);
$workflow->process(/* reader */);
```


Change Log
----------

### Version 0.4 (17 October 2015)

- [#8](https://github.com/plumphp/plum/pull/8) Remove `Workflow::addValueConverter()` and `Workflow::addValueFilter()`
and handle value filters and converter by `::addConverter()` and `::addFilter()`.
- [#9](https://github.com/plumphp/plum/pull/9) Allowing processing of multiple readers.

### Version 0.3.1 (15 May 2015)

- `MappingConverter` allows to copy instead of move values
- Conditional converters can now define the field the filter should be applied on

### Version 0.3 (28 April 2015)

- Refactored `Workflow`
- Improved adding of filters, converters and writers
- Allow callback to be passed directly to filters and converters
- Improved `MappingConverter`

### Version 0.2 (21 April 2015)

- Add value converters and value filters
- Add `MappingConverter`
- Add `LogConverter`
- Filter items if converter returns `null`

### Version 0.1 (18 March 2015)

- Initial release


License
-------

The MIT license applies to plumphp/plum. For the full copyright and license information, please view the
[LICENSE](https://github.com/plumphp/plum/blob/master/LICENSE) file distributed with this source code.
