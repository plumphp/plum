Readers
=======

You read data using an object that implements `ReaderInterface`. This interface extends `\Iterator` interface and
therefore you need to implement its methods. The return value of the `current()` method can be of an arbitrary type,
arrays, objects or scalar values, Plum doesn't care.

ArrayReader
-----------

The `ArrayReader` feeds the elements of an array to the workflow. In addition to the methods required by
`ReaderInterface` it provides a `getData()` methods that returns the full array.

```php
use Cocur\Plum\Reader\ArrayReader;

$reader = new ArrayReader(['Stark', 'Lannister', 'Targaryen', ...]);
$reader->getData(); // -> ['Stark', 'Lannister', 'Targaryen', ...]
```

FinderReader
------------

You can read directories and files using the Symfony Finder component and `FinderReader`.

```php
use Cocur\Plum\Reader\FinderReader;
use Symfony\Component\Finder\Finder;

$finder = new Finder();
// Further configuration of Finder

$reader = new FinderReader($finder);
```
