<p align="center">
    <img src="http://cdn.florian.ec/plum-logo.svg" alt="Plum" width="300">
</p>

> Plum is a data processing pipeline that helps you to write structured, reusable and well tested data processing code.

---

<p align="center">
    <a href="index.md">Index</a>
    <strong>Workflow</strong>
    <a href="readers.md">Readers</a>
    <a href="writers.md">Writers</a>
    <a href="filters.md">Filters</a>
    <a href="converters.md">Converters</a>
</p>

---

Workflow
========

The workflow is the central element in the data processing pipeline provided by Plum. The workflow is represented by
the class `Plum\Plum\Workflow` and you attach filters, converters and writers to it and, when ready, process it by
passing one or more readers to it. The order in which filters, converters and writers can be attached is arbitrary and
the type of the items returned by readers does not matter to Plum.


Table of Contents
-----------------

- [Adding Converters, Filters, and Writers](#adding-converters-filters-and-writers)
- [Retrieving Converters, Filters and Writers](#retrieving-converters-filters-and-writers)
- [Pipeline Order](#pipeline-order)
- [Callback Converters, and Filters](#callback-converters-and-filter)
- [Result](#result)
- [Concatenating Workflows](#concatenating-workflows)
- [Merging Data](#merging-data)
- [Splitting Data](#splitting-data)


Adding Converters, Filters, and Writers
-------------------------------------

The `add*()` methods offer two ways of adding converters, filters, and writers to the workflow. Either you pass the
object as its first argument or you provide an array with options.

### Converters

```php
$converter = ...; // Instance of ConverterInterface or function
$workflow->addConverter($converter);
$workflow->addConverter([
    'converter' => $converter,
    'position'  => Workflow::APPEND,
]);
```

Learn more about [adding converters](#converters.md#adding-converters).

### Value Converters

```php
$converter = ...; // Instance of ConverterInterface or function
$workflow->addConverter([
    'field'     => 'key',
    'converter' => $converter,
]);
```

Learn more about [value converters](#converters.md#value-converters).

### Conditional Converters

```php
$converter = ...; // Instance of ConverterInterface or function
$filter    = ...; // Instance of FilterInterface or function

$workflow->addConverter([
    'converter' => $converter,
    'filter'    => $filter,
]);
$workflow->addConverter([
    'converter'   => $converter,
    'filter'      => $filter,
    'filterField' => 'key'
]);
```

Learn more about [conditional converters](#converters.md#conditional-converters).

### Filters

```php
$filter = ...; // Instance of FilterInterface or function
$workflow->addFilter($filter);
$workflow->addFilter(['filter' => $filter]);
```

Learn more about [adding filters](#filters.md#adding-filters).

### Value Filters

```php
$filter = ...; // Instance of FilterInterface or function
$workflow->addFilter([
    'field'  => 'key',
    'filter' => $filter,
));
```

Learn more about [value filters](#filters.md#value-filters).

### Writers

```php
$writer = ...; // Instance of WriterInterface
$workflow->addWriter($writer);
$workflow->addWriter(['writer' => $writer]);
```

Learn more about [adding writers](writers.md#adding-writers).

### Conditional Writers

```php
$writer = ...; // Instance of WriterInterface
$filter = ...; // Instance of FilterInterface or function
$workflow->addWriter([
    'writer' => $writer,
    'filter  => $filter,
]);
```

Learn more about [conditional writers](writers.md#conditional-writers).


Retrieving Converters, Filters and Writers
------------------------------------------

`Workflow` provides you with getters to retrieve elements from the pipeline.

```php
$workflow->getPipeline(); // -> Plum\Plum\PipelineInterface[]
```

In addition there are methods to retrieve elements of a specific type, i.e., filters, converters and writers.

```php
$workflow->getFilters(); // -> Plum\Plum\Filter\FilterInterface[]
$workflow->getValueFilters(); // -> Plum\Plum\Filter\FilterInterface[]
$workflow->getConverters(); // -> Plum\Plum\Converter\ConverterInterface[]
$workflow->getValueConverters(); // -> Plum\Plum\Converter\ConverterInterface[]
$workflow->getWriters(); // -> Plum\Plum\Writer\WriterInterface[]
```


Pipeline Order
--------------

The pipeline is processed strictly in the order the filters, converters and writers are added to the workflow. You
can pass the position of an pipeline element in the array to the corresponding `add*()` method. There are
two possible values:

- `Workflow::PREPEND`
- `Workflow::APPEND`

```php
$workflow->addFilter(['filter' => $filter, 'position' => Workflow::PREPEND]);
$workflow->addConverter(['converter' => $converter, 'position' => Workflow::PREPEND]);
$workflow->addWriter(['converter' => $converter, 'position' => Workflow::APPEND]);
```


Callback Converters and Filters
--------------------------------

`Plum\Plum\Converter\CallbackConverter` is a converter that executes a callback to convert an item. When adding a 
converter or value converter to a `Workflow` you can just pass the callback and the workflow will automatically create a 
`CallbackConverter`. This works for both the direct as well as the array syntax.

```php
$workflow->addConverter(function ($item) { return strtoupper($item); });
$workflow->addConverter([
    'converter' => function ($item) { return strtoupper($item); }
]);
$workflow->addConverter([
    'field'     => 'foo', 
    'converter' => function ($item) { return strtoupper($item); }
]);
```

`Plum\Plum\Filter\CallbackFilter` is a filter that executes a callback and filters the item based on the return value 
of the callback. Just like with converters you can just pass the callback and the workflow will create a 
`CallbackConverter` for you.

```php
$workflow->addFilter(function ($item) { return !empty($item['price']; });
$workflow->addFilter([
    'filter' => function ($item) { return !empty($item['price']; }
]);
$workflow->addFilter([
    'field'  => 'foo', 
    'filter' => function ($item) { return $item === 'bar'; }
]);
```

In addition it is also possible to use callbacks as filters in conditional converters.


Result
------

The `process()` method returns an instance of `Plum\Plum\Result`. This object contains information and errors
collected during the processing.

```php
$result = $workflow->process($reader);
$result->getReadCount(); // -> int
$result->getWriteCount(); // -> int
$result->getItemWriteCount(); // -> int
$result->getErrorCount(); // -> int
$result->getExceptions(); // -> \Exception[]
```

Plum counts two different types of writes. The write counter returned by `getWriteCount()` is increased every time an
item is written. If you have 3 items and 2 writers in your workflow the write counter will be `6`. In constrast the
item write counter returned by `getItemWriteCount()` is only increased once for every item. That is, if you have 3
items and 2 writers in your workflow, the item writer counter will return `3`.


Concatenating Workflows
-----------------------

On of the most powerful features of Plum is the ability to concatenate workflows. The `Plum\Plum\WorkflowConcatenator`
implements both the `Plum\Plum\ReaderInterface` and the `Plum\Plum\WriterInterface` and must be added as a writer
to the first workflow and as a reader to the second workflow.

```php
use Plum\Plum\WorkflowConcatenator;

$concatenator = new WorkflowConcatenator();

// Add concatenator as writer to first workflow and process it.
$workflow1->addWriter($concatenator);
$workflow1->process($reader);

// Process the second workflow with the concatenator as reader.
$workflow->process($concatenator):
```


Merging Data
------------

You can process data from multiple readers with one call to `process()`. In practice you can use this to merge 
multiple data sources into a single target.

```php
use Plum\Plum\Workflow;

$workflow = new Workflow();
$workflow->process([$reader1, $reader2]);
```


Splitting Data
--------------

Plum also allows you to split data from one source into multiple targets. You can leverage the power of conditional
writers to achieve this.

In the following example `$filter2` should be the negation of `$filter1`.

```php
$workflow->addWriter(['writer' => $writer1, 'filter' => $filter1]);
$workflow->addWriter(['writer' => $writer2, 'filter' => $filter2]);
```

---

<p align="center">
    <a href="index.md">Index</a>
    <strong>Workflow</strong>
    <a href="readers.md">Readers</a>
    <a href="writers.md">Writers</a>
    <a href="filters.md">Filters</a>
    <a href="converters.md">Converters</a>
</p>

