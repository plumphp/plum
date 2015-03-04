Readers
=======

You read data using an object that implements `ReaderInterface`. This interface extends `\IteratorAggregate` interface
and therefore you need to implement the `getIterator()` method.


Table of Contents
-----------------

- [ArrayReader](#arrayreader)
- [ExcelReader](#excelreader)
- [FinderReader](#finderreader)
- [JsonFileReader](#jsonfilereader)
- [JsonReader](#jsonreader)
- [PdoStatementReader](#pdostatementreader)
- [Custom Readers](#custom-readers)
- [PHP 5.5 and Generators](#php-55-and-generators)


ArrayReader
-----------

The `ArrayReader` feeds the elements of an array to the workflow. In addition to the methods required by
`ReaderInterface` it provides a `getData()` methods that returns the full array.

```php
use Plum\Plum\Reader\ArrayReader;

$reader = new ArrayReader(['Stark', 'Lannister', 'Targaryen', ...]);
$reader->getData(); // -> ['Stark', 'Lannister', 'Targaryen', ...]
```


ExcelReader
-----------

You can use the `ExcelReader` to read data from an Excel (`.xlsx` or `.xls`) file. You need to add the `plum-excel`
package to your project using Composer: `composer require plumphp/plum-excel:@stable`. Plum uses
[PHPExcel](https://github.com/PHPOffice/PHPExcel) to actually read the Excel files.

```php
use Plum\PlumExcel\ExcelReader;

$reader = new ExcelReader(PHPExcel_IOFactory::load('countries.xlsx'));
$reader->setHeaderRow(0); // First row contains the header
```


FinderReader
------------

You can read directories and files using the [Symfony Finder](http://symfony.com/doc/current/components/finder.html) 
component and `FinderReader`. You need to add the `plum-finder` package to your project using Composer:
`composer require plumphp/plum-finder:@stable`.

```php
use Plum\PlumFinder\FinderReader;
use Symfony\Component\Finder\Finder;

$finder = new Finder();
// Further configuration of Finder

$reader = new FinderReader($finder);
```

JsonReader
----------

`JsonReader` reads a JSON string. If you want to read a `.json` file checkout [JsonFileReader](#jsonfilereader). You
need to add the `plum-json` package to your project using Composer: `composer require plumphp/plum-json:@stable`.

```php
use Plum\PlumJson\JsonReader;

$reader = new JsonReader('[{'key1': 'value1', 'key2': 'value2'}]');
$reader->getIterator(); // -> \ArrayIterator
$reader->count();
```

JsonFileReader
--------------

`JsonFileReader` reads a `.json` file. You need add the `plum-json` package to your project using Composer:
`composer require plumphp/plum-json:@stable`.

```php
use Plum\PlumJson\JsonFileReader;

$reader = new JsonFileReader('foo.json');
$reader->getIterator(); // -> \ArrayIterator
$reader->count();
```

PdoStatementReader
------------------

`PdoStatementReader` returns an iterator for the result set of a `PDOStatement`. The `execute()` method has to be
called before. You need to add the `plum-pdo` package to your project using Composer: 
`composer require plumphp/plum-pdo:@stable`.
 
```php
use Plum\PlumPdo\PdoStatementReader;

$statement = $pdo->prepare('SELECT * FROM users WHERE age >= :min_age');
$statement->bindValue(':min_age', 18);
$statement->execute();

$reader = new PdoStatementReader($statement);
$reader->getIterator(); // -> \ArrayIterator
$reader->count();
```


Custom Readers
--------------

As mentioned in the introduction `ReaderInterface` extends `IteratorAggregate` and readers therefore have to
implement the `getIterator()` method.

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
