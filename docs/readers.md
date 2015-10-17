<p align="center">
    <img src="http://cdn.florian.ec/plum-logo.svg" alt="Plum" width="300">
</p>

> Plum is a data processing pipeline that helps you to write structured, reusable and well tested data processing code.

---

<p align="center">
    <a href="index.md">Index</a>
    <strong>Readers</strong>
    <a href="writers.md">Writers</a>
    <a href="filters.md">Filters</a>
    <a href="converters.md">Converters</a>
</p>

---

You read data using an object that implements `ReaderInterface`. This interface extends `\IteratorAggregate` and
 `\Countable` interfaces and therefore you need to implement the `getIterator()` and `count()` methods.


Table of Contents
-----------------

- [Adding Readers](#adding-readers)
- [Default Readers](#default-readers)
- [Custom Readers](#custom-readers)
- [PHP 5.5 and Generators](#php-55-and-generators)


Adding Readers
--------------

Readers are different than all other pipeline elements you can add to a workflow because you don't *add* them to the
workflow, but pass them to the workflows `process()` method which starts processing the read items.

```php
use Plum\Plum\Reader\ArrayReader;

$reader = new ArrayReader([2, 3, 5, 7, 11]);

$workflow->process($reader);
```

Instead of an instance of `ReaderInterface` you can also pass an array that contains multiple instances of 
`ReaderInterface` to `process()`.

```php
use Plum\Plum\Reader\ArrayReader;

$reader1 = new ArrayReader([2, 3, 5, 7, 11]);
$reader2 = new ArrayReader([101, 103, 107, 109, 113]);

$workflow->process([$reader1, $reader2]);
```


Default Readers
---------------

The core Plum package contains `ArrayReader`.


### `ArrayReader`

The `Plum\Plum\Reader\ArrayReader` feeds the elements of an array to the workflow. In addition to the methods required
by `ReaderInterface` it provides a `getData()` methods that returns the full array.

```php
use Plum\Plum\Reader\ArrayReader;

$reader = new ArrayReader(['Stark', 'Lannister', 'Targaryen', ...]);
$reader->getData(); // -> ['Stark', 'Lannister', 'Targaryen', ...]
```


Custom Readers
--------------

As mentioned in the introduction `ReaderInterface` extends `IteratorAggregate` and `Countable` and readers therefore
have to implement the `getIterator()` and `count()` methods.

```php
use Plum\Plum\Reader\ReaderInterface;

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
    
    public function count()
    {
        return count($this->collection();
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

---

<p align="center">
    <a href="index.md">Index</a>
    <strong>Readers</strong>
    <a href="writers.md">Writers</a>
    <a href="filters.md">Filters</a>
    <a href="converters.md">Converters</a>
</p>

