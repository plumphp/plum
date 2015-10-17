<p align="center">
    <img src="http://cdn.florian.ec/plum-logo.svg" alt="Plum" width="300">
</p>

> Plum is a data processing pipeline that helps you to write structured, reusable and well tested data processing code.

---

<p align="center">
    <a href="index.md">Index</a>
    <a href="workflow.md">Workflow</a>
    <a href="readers.md">Readers</a>
    <strong>Writers</strong>
    <a href="filters.md">Filters</a>
    <a href="converters.md">Converters</a>
</p>

---

<h1 align="center">
    <img src="http://cdn.florian.ec/plum-write.svg" alt="filter" width="300">
</h1>

Use writers to write the result of the workflow. The target doesn't necessarily have to write to a persistent storage,
you can also write, for example, into arrays or objects. Writers must implement the `WriterInterface` that provides
three methods: `writeItem()`, `prepare()` and `finish()`. The workflow calls `prepare()` before it reads the first item
and `finish()` after it read the last item.

Multiple writers can be added to a workflow in any ordering. Therefore it is possible to filter the read items, write
them somewhere, further filter them and then write them elsewhere.


Table of Contents
-----------------

- [Adding Writers](#adding-writers)
- [Conditional Writers](#conditional-writers)
- [Default Writers](#default-writers)
- [Custom Writers](#custom-writers)


Adding Writers
--------------

Writer can be added to a workflow by calling `addWriter()` with an instance of `Plum\Plum\Writer\WriterInterface`.

```php
use Plum\Plum\Writer\ArrayWriter;

$workflow = new Workflow();
$workflow->addWriter(['writer' => new ArrayWriter()]);
```

You can also directly pass the writer to `addWriter()` if you don't want to set any other options.

```php
use Plum\Plum\Writer\ArrayWriter;

$workflow = new Workflow();
$workflow->addWriter(new ArrayWriter());
```

By default writers are appended at the end of the workflow. You can use the `position` option to change the default
behaviour.

```php
use Plum\Plum\Writer\ArrayWriter;

$workflow = new Workflow();
$workflow->addWriter([
    'writer'   => new ArrayWriter(),
    'position' => Workflow::PREPEND
]);
```


Conditional Writers
-------------------

Very much like [Conditional Converters](converters.md#conditional-converters) writers can be conditional and an item
will only be written if a filter returns `true` for the given item. Filters can be an instance of 
`Plum\Plum\Filter\FilterInterface` or a function.

```php
$workflow->addWriter([
    'writer' => $writer,
    'filter' => $filter,
]);
```

Unlike converters, writers currently do not support the `filterField` option.

Conditional writers can be used to split data from one source and write it to different targets. For example,

```php
$workflow->addWriter([
    'writer' => $writer1,
    'filter' => function ($item) { return $item['year'] < 2010; },
]);
$workflow->addWriter([
    'writer' => $writer2,
    'filter' => function ($item) { return $item['year'] >= 2010; },
]);
```


Default Writers
---------------

The core Plum package currently contains only `ArrayWriter`.

### `ArrayWriter`

The `Plum\Plum\Writer\ArrayWriter` writes the data into an array that can be retrieved using the `getData()` method.

```php
use Plum\Plum\Writer\ArrayWriter;

$writer = new ArrayWriter();
// Workflow processing
$writer->getData() // -> [...]
```


---

<p align="center">
    <a href="index.md">Index</a>
    <a href="workflow.md">Workflow</a>
    <a href="readers.md">Readers</a>
    <strong>Writers</strong>
    <a href="filters.md">Filters</a>
    <a href="converters.md">Converters</a>
</p>
