Workflow
========

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

- [Adding Converters, Filters, and Writers](#adding-converters-filters-and-writers)
- [Conditional Converters](#conditional-converters)
- [Pipeline Order](#pipeline-order)
- [Callback Converters, and Filters](#callback-converters-and-filter)
- [Result](#result)
- [Concatenating Workflows](#concatenating-workflows)


Adding Converters, Filters, and Writers
-------------------------------------

The `add*()` methods offer two ways of adding converters, filters, and writers to the workflow. Either you pass the
object as its first argument or you provide an array with options.

### Converters

```php
$workflow->addConverter($converter);
$workflow->addConverter(['converter' => $converter]);
```

### Value Converters

```php
$workflow->addValueConverter($converter, ['key']);
$workflow->addValueConverter(['converter' => $converter, 'field' => ['key']]);
```

### Filters

```php
$workflow->addFilter($filter);
$workflow->addFilter(['filter' => $filter]);
```

### Value Filters

```php
$workflow->addValueFilter($filter, ['key']);
$workflow->addValueFilter(['filter' => $filter, 'field' => ['key']]);
```

### Writers

```php
$workflow->addWriter($writer);
$workflow->addWriter(['writer' => $writer]);
```


Conditional Converters
----------------------

The `addConverter()` method accepts an filter, that is, an instance `Plum\Plum\Filter\FilterInterface`. You need
to call `addConverter()` with an array as its element. If a
filter is provided the converter is only applied to an item if the filter returns `true` for the given item. Otherwise
the original item is returned by the converter.

```php
$converter = new CallbackConverter(function ($item) { return strtoupper($item); });
$filter    = new CallbackFilter(function ($item) { return preg_match('/foo/', $item); });
$workflow->addConverter(['converter' => $converter, 'filter' => $filter]);

// "foobar" -> "FOOBAR"
// "bazbar" -> "bazbar"
```

Conditional converters also work for value converters:

```php
$converter = new CallbackConverter(function ($item) { return strtoupper($item); });
$filter    = new CallbackFilter(function ($item) { return preg_match('/foo/', $item); });
$workflow->addValueConverter(['converter' => $converter, 'filter' => $filter], ['k']);

// ["k" => "foobar"] -> ["k" => "FOOBAR"]
// ["k" => "bazbar"] -> ["k" => "bazbar"]
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

`CallbackConverter` is a converter that executes a callback to convert an item. When adding a converter or value
converter to a `Workflow` you can just pass the callback and the workflow will automatically create a 
`CallbackConverter`. This works for both the direct as well as the array syntax.

```php
$workflow->addConverter(function ($item) { return strtoupper($item); });
$workflow->addConverter(['converter' => function ($item) { return strtoupper($item); }]);
$workflow->addValueConverter(function ($item) { return strtoupper($item); }, ['foo']);
$workflow->addValueConverter(['converter' => function ($item) { return strtoupper($item); }, 'field' => ['foo']]);
```

`CallbackFilter` is a filter that executes a callback and filters the item based on the return value of the callback.
Just like with converters you can just pass the callback and the workflow will create a `CallbackConverter` for you.

```php
$workflow->addFilter(function ($item) { return strtoupper($item); });
$workflow->addFilter(['filter' => function ($item) { return strtoupper($item); }]);
$workflow->addValueFilter(function ($item) { return strtoupper($item); }, ['foo']);
$workflow->addValueFilter(['filter' => function ($item) { return strtoupper($item); }, 'field' => ['foo']]);
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

---

<p align="right">
    Continue with <a href="readers.md">Readers</a>
</p>
