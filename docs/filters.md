Filters
=======

You use filters to remove items from the pipeline. Filters implement `FilterInterface` and must provide a `filter()`
method. Every item of the pipeline is passed to the filter and if the return value evaluates to `false` (`false`,
`null`, `0`, ...) the item is no further processed.

Plum comes with some default filters, but you can also create your own custom filters.


Table of Contents
-----------------

- [CallbackFilter](#callbackfilter)
- [FileExtensionFilter](#fileextensionfilter)
- [SkipFirstFilter](#skipfirstfilter)
- [Custom Filters](#custom-filters)


CallbackFilter
--------------

The `CallbackFilter` calls a function and checks the return value of that function.

```php
use Plum\Plum\Filter\CallbackFilter;

$filter = new CallbackFilter(function ($item) {
    return preg_match('/https?:\/\/[a-z0-9-]+\.[a-z]+/', $item);
});
$filter->filter('https://florian.ec'); // -> true
```


FileExtensionFilter
-------------------

Checks if the file extension of a file name matches.

```php
use Plum\Plum\Filter\FileExtensionFilter;

$filter = new FileExtensionFilter('md');
$filter->filter('README.md'); // -> true
$filter->filter('README.html'); // -> false
```

If the item is are more complex structure, for example, an array or an object `FileExtensionFilter` uses Symfony's
[PropertyAccess](http://symfony.com/doc/current/components/property_access/introduction.html) to retrieve the filename
from the item. You can pass the path to the property as the second argument to the constructor.

```php
use Plum\Plum\Filter\FileExtensionFilter;

$filterArray = new FileExtensionFilter('md', '[filename]');
$filterArray->filter(['filename' => 'README.md']); // -> true
$filterArray->filter(['filename' => 'README.html']); // -> false

$item = new stdClass();
$item->filename = 'README.md';
$filterObject = new FileExtensionFilter('md', 'filename');
$filterObject->filter($item); // -> true
$item->filename = 'README.html';
$filterObject->filter($item); // -> false
```

The extension passed to the constructor can also be an array. The filter returns `true` if the given item matches any
of the extensions in the array.

```php
$filter = new FileExtensionFilter(['md', 'html']);
$filter->filter('file.md'); // -> true
```


SkipFirstFilter
--------------

The `SkipFirstFilter` skips the first items. The amount of items skipped is passed in as a constructor argument. It
can be used to skip the header of a CSV or Excel file.

```php
use Plum\Plum\Filter\SkipFirstFilter;

$filter = new SkipFirstFilter(1);
$filter->filter('foo'); // -> false
$filter->filter('bar'); // -> true
```


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
