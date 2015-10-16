<p align="center">
    <img src="http://cdn.florian.ec/plum-logo.svg" alt="Plum" width="300">
</p>

> Plum is a data processing pipeline that helps you to write structured, reusable and well tested data processing code.

Developed by [Florian Eckerstorfer](https://florian.ec) in Vienna, Europe.

---

<p align="center">
    <img src="http://cdn.florian.ec/plum-filter.svg" alt="filter" width="300">
</p>

You use filters to remove items from the pipeline. A filter can be either an instance of 
`Plum\Plum\Filter\FilterInterface` or a function. Every item of the pipeline is passed to the filter and if the return
value evaluates to `false` (`false`, `null`, `0`, ...) the item is no further processed.

Adding Filters
--------------

An filter is attached to the workflow by calling the `addFilter()` method. The `addFilter()` method expects an array
with options as its parameter.

In the following example we are going to attach `Plum\Plum\Filter\SkipFirstFilter`, which filters the first `x`
items from the workflow.

```php
$workflow = new Workflow();
$workflow->addFilter(['filter' => new SkipFirstFilter(5)]);
```

Since the above code is pretty common there is a shortcut:

```php
$workflow = new Workflow();
$workflow->addFilter(new SkipFirstFilter(5));
```

We can also use a function as filter:

```php
$workflow = new Workflow();
$workflow->addFilter(['filter' => function ($item) { return !empty($item['price']) }]);
```

There is also a shortcut for passing in a function:

```php
$workflow = new Workflow();
$workflow->addFilter(function ($item) { return !empty($item['price']) });
```

By default filters are always prepended at the end of the workflow. However, you can change the default behaviour
by using the `position` option.

```php
$workflow = new Workflow();
$workflow->addFilter([
    'filter'   => new SkipFirstFilter(5),
    'position' => Workflow::PREPEND,
]);
```

In all the previous examples the full item is passed to the filter. However, if you pass the `field` option to
`addFilter()` only this field is passed to the filter. This gives you the ability to reuse filters more easily, since
your filters do not depend on the structure of the item. We call these type of filters *value filters* because they
filter the item based on the value of a specific field of the item.

```php
$workflow = new Workflow();
$workflow->addFilter([
    'field   => 'age',
    'filter' => function ($v) { return $v === 42; },
]);
```

Internally Plum uses [Vale](https://github.com/cocur/vale) to access the value of the field, that is, you can not only
access values in associative arrays, but even values in deeply nested arrays and objects.

```php
$workflow = new Workflow();
$workflow->addFilter([
    'field   => ['children'][0]['age'],
    'filter' => function ($v) { return $v === 42; },
]);
```

Default Filters
---------------

Plum ships with some default filters. Additional filters are available in official and third-party packages and you
can always implement your own filters.

### `SkipFirstFilter`

The `Plum\Plum\Filter\SkipFirstFilter` removes the first `x` elements from a workflow. The first and only argument
to the constructor is the number of elements it should skip.

```php
use Plum\Plum\Filter\SkipFirstFilter;

$workflow = new Workflow();
$workflow->addFilter(new SkipFirstFilter(5));
```

### `CallbackFilter`

`Plum\Plum\Filter\CallbackFilter` calls a function to filter the item.

```php
use Plum\Plum\Filter\CallbackFilter;

$workflow = new Workflow();
$workflow->addFilter(new CallbackFilter(function ($item) {
    return !empty($item['price']);
}));
```

However, you can achieve the same result by passing just the function to `addFilter()`, in fact, internally when a
function is passed to `addFilter()` it is automatically wrapped in a `CallbackFilter`.


Custom Filters
--------------

You can create a custom filter by creating a class that implements `Plum\Plum\Filter\FilterInterface`. The interface
requires you to implement a `filter()` method that expects a single argument and returns either `true` or `false`.
Plum processes items of all types, simple scalar types (such as strings or integers) as well as complex types (arrays
or objects) and therefore Plum does not guarantee the type of the item passed to `filter()`.

```php

use Plum\Plum\Filter\FilterInterface;

class RegExpFilter implements FilterInterface
{
    private $pattern;

    public function __construct($pattern)
    {
        $this->pattern = $pattern;
    }

    public function filter($item)
    {
        return 1 === preg_match($this->pattern, $item);
    }
}
```

---

<p align="right">
    Continue with <a href="converters.md">Converters</a>
</p>

