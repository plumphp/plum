<p align="center">
    <img src="http://cdn.florian.ec/plum-logo.svg" alt="Plum" width="300">
</p>

> Plum is a data processing pipeline that helps you to write structured, reusable and well tested data processing code.

---

<p align="center">
    <a href="index.md">Index</a>
    <a href="workflow.md">Workflow</a>
    <a href="readers.md">Readers</a>
    <a href="writers.md">Writers</a>
    <a href="filters.md">Filters</a>
    <strong>Converters</strong>
    <a href="extensions.md">Extensions</a>
</p>

---

<h1 align="center">
    <img src="http://cdn.florian.ec/plum-convert.svg" alt="filter" width="300">
</h1>

Converters take an item and convert it into something else. A converter can be either a class that implements
`Plum\Plum\Converter\ConverterInterface` or a function.


Table of Contents
-----------------

- [Adding Converters](#adding-converters)
- [Value Converters](#value-converters)
- [Conditional Converters](#conditional-converters)
- [Default Converters](#default-converters)
- [Custom Converters](#custom-converters)


Adding Converters
-----------------

A converter is attached to the workflow by calling the `addConverter()` method. The `addConverter()` method expects an 
array with options as its parameter.

In the following example we are going to attach `Plum\Plum\Converter\NullConverter`, which replaces `null` values with
the empty string.

```php
use Plum\Plum\Converter\NullConverter;

$workflow = new Workflow();
$workflow->addConverter(['converter' => new NullConverter()]);
```

Since the above code is pretty common you can pass the object directly to `addConverter()`:

```php
use Plum\Plum\Converter\NullConverter;

$workflow = new Workflow();
$workflow->addConverter(new NullConverter());
```

We can also use a function as converter:

```php
$workflow = new Workflow();
$workflow->addConverter(['converter' => function ($item) {
    $item['name'] = mb_strtoupper($item['name'];
    return $item;
}]);
```

There is also a shortcut for passing in a function:

```php
$workflow = new Workflow();
$workflow->addConverter(function ($item) {
    $item['name'] = mb_strtoupper($item['name'];
    return $item;
});
```

By default converters are always prepended at the end of the workflow. However, you can change the default behaviour
by using the `position` option.

```php
$workflow = new Workflow();
$workflow->addConverter([
    'converter' => new NullConverter(),
    'position'  => Workflow::PREPEND,
]);
```


Value Converters
----------------

Often you don't want to convert the whole item, but only a single field and Plum therefore supports value converters.
Plum uses [Vale](https://github.com/cocur/vale) to retrieve a field from an arbitrary array or object, apply a converter
and stores the converted value back into its original position. To use a value converter you need to pass the access
key to the value as the `field` option.

```php
$writer = new ArrayWriter();
$workflow = new Workflow();
$workflow->addConverter([
    'field'     => 'foo',
    'converter' => function ($v) { return $v*2; }
);
$workflow->addWriter($writer);
$workflow->process(new ArrayReader(['foo' => 21, 'bar' => 11, 'qoo' => 69, 'qoz' => 100]));

$writer->getData(); // -> ['foo' => 42, 'bar' => 11, 'qoo' => 69, 'qoz' => 100]
```

Value converters also support filters and you can prepend them to the workflow.


Conditional Converters
----------------------

By default converters are applied to every item passed through the workflow. However, sometimes you only want to convert
items that match a certain criteria. You can use *conditional converters* for htis. 

The `addConverter()` method accepts an filter, that is, an instance `Plum\Plum\Filter\FilterInterface`. If a
filter is provided the converter is only applied to an item if the filter returns `true` for the given item. Otherwise
the original item is returned by the converter.

```php
$workflow->addConverter([
    'converter' => function ($item) { return mb_strtoupper($item); },
    'filter'    => function ($item) { return preg_match('/foo/', $item); }
]);

// "foobar" -> "FOOBAR"
// "bazbar" -> "bazbar"
```

Conditional converters also work for value converters.

```php
$workflow->addConverter([
    'field'     => 'k',
    'converter' => function ($item) { return mb_strtoupper($item); }, 
    'filter'    => function ($item) { return !empty($item['a']); }
]);

// ["k" => "foobar", "a" => "xyz"] -> ["k" => "FOOBAR", "a" => "xyz"]
// ["k" => "foobar", "a" => ""] -> ["k" => "foobar", "a" => ""]
// ["k" => "bazbar", "a" => "xyz"] -> ["k" => "bazbar", "a" => "xyz"]
```

As you can see in the previous example, the `field` option is only applied to the converter, not the filter. If you
want to filter on a specific field you can use the `filterField` option.

```php
$workflow->addConverter([
    'field'        => 'k',
    'converter'    => function ($item) { return mb_strtoupper($item); },
     'filterField' => 'k',
    'filter'       => function ($item) { return preg_match('/foo/', $item); }
]);

// ["k" => "foobar"] -> ["k" => "FOOBAR"]
// ["k" => "bazbar"] -> ["k" => "bazbar"]
```


Default Converters
------------------

The core Plum library contains some basic and general converters.


### `CallbackConverter`

The `Plum\Plum\Converter\CallbackConverter` calls a callback to convert a given item.

```php
use Plum\Plum\Converter\CallbackConverter;

$converter = new CallbackConverter(function ($item) { return mb_strtoupper($item); });
$converter->convert('foo'); // -> FOO
```

As you can see that this has the same effect as passing a function directly to `addConverter()` and in fact, if you
pass a function to `addConverter()` Plum internally wraps the function in a `CallbackConverter`.


### `HeaderConverter`

You can use `Plum\Plum\Converter\HeaderConverter` to make the first item in the workflow the header and use these values
as keys for the other items. This is the case, for example, in CSV or Excel files where the first row contains the
names of the columns. 

```php
use Plum\Plum\Converter\HeaderConverter;

$workflow->addConverter(new HeaderConverter());
$workflow->addFilter(new SkipFirstFilter(1)); // Remove header
$workflow->process(new ArrayReader([['name', 'age'], ['x', 21], ['y', 42]]));
// -> [["name" => "x", "age" => 21], ["name" => "y", "age" => 42]]
```


### `LogConverter`

`Plum\Plum\Converter\LogConverter` logs the item to a [PSR-3 compatible logger](http://www.php-fig.org/psr/psr-3/). The
item is not modified by this converter. In addition to the logger you can also pass the desired log level and the log
message to the constructor.

```php
use Plum\Plum\Converter\LogConverter;

$converter = new LogConverter($logger, 'debug', 'Item:');
$converter->convert(['foo' => 'bar']);
// Will call
// $logger->log('debug', 'Item:', ['foo' = 'bar']);
```


### `MappingConverter`

The `MappingConverter` can be used to maps a value from one element to another. The converter uses
[Vale](https://github.com/cocur/vale) to target elements in complex, nested data structures (objects and arrays); the
given item therefore can be even a complex object. Vale tries a lot of different variants to access an element, thus
it should be possible to transform nearly every item.

```php
use Plum\Plum\Converter\MappingConverter;

$converter = new MappingConverter();
$converter->addMapping(['foo'], ['bar']);
$converter->convert(['foo' => 'foobar']); // -> ['bar' => 'foobar']
```

If two or more mappings which point to the same target element are given, the last one added to the converter takes
precedence and the first element will be lost.

```php
$converter = new MappingConverter();
$converter->addMapping(['foo'], ['bar']);
$converter->addMapping(['qoo'], ['bar']);
$converter->convert(['foo' => 'foobar', 'qoo' => 'qoobar']); // -> ['bar' => 'qoobar']
```

If the `from` mapping is empty, the converter creates a new array and sets the item as `to` element in that array:

```php
$converter->addMapping('', 'bar');
$converter->convert('foobar'); // -> ['bar' => 'foobar']
```

In contrast, if the `to` mapping is empty the `from` value will returned as item.
 
```php
$converter->addMapping('bar', '');
$converter->convert(['bar' => 'foobar'); // -> 'foobar'
```

### `NullConverter`

`Plum\Plum\Converter\NullConverter` converts `null` values into a null value (by default the empty string).

```php
use Plum\Plum\Converter\NullConverter;

$converter = new NullConverter();
$converter->convert(null); // -> ""
```

You can also define which value `null` values should have assigned to.

```php
use Plum\Plum\Converter\NullConverter;

$converter = new NullConverter(0);
$converter->convert(null); // -> 0
```


Custom Converters
-----------------

You can add custom converters by creating a class that implements `Plum\Plum\Converter\ConverterInterface`.

```php
use Plum\Plum\Converter\ConverterInterface;

class Rot13Converter implements ConverterInterface
{
    public function convert($item)
    {
        return str_rot13($item);
    }
}
```

---

<p align="center">
    <a href="index.md">Index</a>
    <a href="workflow.md">Workflow</a>
    <a href="readers.md">Readers</a>
    <a href="writers.md">Writers</a>
    <a href="filters.md">Filters</a>
    <strong>Converters</strong>
    <a href="extensions.md">Extensions</a>
</p>
