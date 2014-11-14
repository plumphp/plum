Readers
=======

You read data using an object that implements `ReaderInterface`. This interface extends `\IteratorAggregate` interface
and therefore you need to implement the `getIterator()` method.

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

Custom Readers
--------------

As mentioned in the introduction `ReaderInterface` extends `IteratorAggregate` and readers therefore have to
implement the `getIterator()` method.

```php
use Cocur\Plum\Reader\ReaderInterface;

class CollectionReader implements ReaderInterface
{
    private $collection = [];
    
    public function add($item)
    {
        $this->collection[] = $item;
    }
    
    public function getIterator()
    {
        return new ArrayIterator($this->collection);
    }
}
```

PHP 5.5 and Generators
----------------------

If you are using PHP 5.5+ the `getIterator()` method can also return a generator.

```php
public function getIterator()
{
    foreach ($this->collection as $item) {
        yield $item;
    }
}
```
