<p align="center">
    <img src="http://cdn.florian.ec/plum-logo.svg" alt="Plum" width="300">
</p>

> Plum is a data processing pipeline that helps you to write structured, reusable and well tested data processing code.

Developed by [Florian Eckerstorfer](https://florian.ec) in Vienna, Europe.


Overview
--------

An overview of the features and a quick installation guide is provided in the 
[README](https://github.com/plumphp/plum/blob/master/README.md).

The base `plum` package contains the base classes, interfaces and some very generic filters, converters, readers and
writers. Specific readers, writers, filter and converters can be added as separate packages. A list of 
[Plum packages](https://github.com/plumphp) can be found on Github.

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
$workflow->process($reader);
```

Table of Contents
-----------------

- [Workflow](workflow.md)
- [Readers](readers.md)
- [Writers](writers.md)
- [Filters](filters.md)
- [Converters](converters.md)

---

<p align="right">
    Continue with <a href="workflow.md">Workflow</a>
</p>
