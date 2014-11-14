Workflow
========

```php
use Cocur\Plum\Workflow;

$workflow = new Workflow();
$workflow->addFilter($filter)
         ->addConverter($converter)
         ->addWriter($writer);
$workflow->process($reader);
```

Conditional Converters
----------------------

The `addConverter()` method accepts an optional second parameter of type `Cocur\Plum\Filter\FilterInterface`. If a
filter is provided the converter is only applied to an item if the filter returns `true` for the given item. Otherwise
the original item is returned by the converter.

```php
$converter = new CallbackConverter(function ($item) { return strtoupper($item); });
$filter    = new CallbackFilter(function ($item) { return preg_match('/foo/', $item); });
$workflow->addConverter($converter, $filter);

// "foobar" -> "FOOBAR"
// "bazbar" -> "bazbar"
```

Pipeline Order
--------------

The pipeline is processed strictly in the order the filters, converters and writers are added to the workflow. You
can pass the position of an pipeline element as the last parameter to the corresponding `add*()` method. There are
two possible values:

- `Workflow::PREPEND`
- `Workflow::APPEND`

```php
$workflow->addFilter($filter, Workflow::PREPEND);
$workflow->addConverter($converter, null, Workflow::PREPEND);
$workflow->addWriter($converter, null, Workflow::APPEND);
```


Result
------

The `process()` method returns an instance of `Cocur\Plum\Result`. This object contains information and errors
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
