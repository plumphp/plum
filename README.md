<p align="center">
    <img src="http://cdn.florian.ec/plum-logo.svg" alt="Plum" width="300">
</p>

> Plum is a data processing pipeline that helps you to write structured, reusable and well tested data processing code.

[![Build Status](https://travis-ci.org/plumphp/plum.svg?branch=master&style=flat-square)](https://travis-ci.org/plumphp/plum)
[![Windows Build status](https://ci.appveyor.com/api/projects/status/giayhr2232tlakav?svg=true)](https://ci.appveyor.com/project/florianeckerstorfer/plum)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/plumphp/plum/badges/quality-score.png?b=master&style=flat-square)](https://scrutinizer-ci.com/g/plumphp/plum/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/plumphp/plum/badges/coverage.png?b=master&style=flat-square)](https://scrutinizer-ci.com/g/plumphp/plum/?branch=master)
[![StyleCI](https://styleci.io/repos/30204360/shield)](https://styleci.io/repos/30204360)

[![MIT License](https://img.shields.io/packagist/l/plumphp/plum.svg?style=flat-square)](http://opensource.org/licenses/MIT)
[![Latest Release](https://img.shields.io/packagist/v/plumphp/plum.svg?style=flat-square)](https://packagist.org/packages/plumphp/plum)
[![Total Downloads](https://img.shields.io/packagist/dt/plumphp/plum.svg?style=flat-square)](https://packagist.org/packages/plumphp/plum)


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


Installation
------------

You can install Plum using [Composer](http://getcomposer.org) (recommended) or by downloading a
[release](https://github.com/plumphp/plum/releases).

```shell
$ composer require plumphp/plum
```


Contribute
----------

If you want to help us improve Plum you can contribute in a number of different ways:

- Fix bugs or add additional features (check our [Waffle board](https://waffle.io/plumphp/plum) to see features and bugs that are ready to go)
- Help others by providing support to others: [Issues](https://github.com/plumphp/plum/issues) and [Chat](https://gitter.im/plumphp/plum)
- Improve the [documentation](https://github.com/plumphp/plum/blob/master/docs/index.md)
- Spread the work by tweeting, blogging or talking about Plum at your local user group

When you need help or have any questions feel free to [contact us](#support).


Support
-------

If you need help getting started, run into problems or want to discuss new features you can also contact us. You can
either create a new [issue](https://github.com/plumphp/plum/issues), you can join our
[Gitter chat](https://gitter.im/plumphp/plum) or ping us on Twitter [@cocurco](https://twitter.com/cocurco).

**Please be excellent to each other.**


Authors
-------

- [Florian Eckerstorfer](https://florian.ec) ([Twitter](https://twitter.com/florian_), [Donate €5](https://paypal.me/florianec/5))
- Some [fantastic contributors](https://github.com/plumphp/plum/graphs/contributors)

*Plum is a [Cocur](http://cocur.co) open source project.*


Change Log
----------

See [CHANGELOG.md](https://github.com/plumphp/plum/blob/master/CHANGELOG.md).

License
-------

The MIT license applies to plumphp/plum. For the full copyright and license information, please view the
[LICENSE](https://github.com/plumphp/plum/blob/master/LICENSE) file distributed with this source code.
