Readers
=======

You read data using an object that implements `ReaderInterface`. This interface extends `\IteratorAggregate` interface
and therefore you need to implement the `getIterator()` method.


Table of Contents
-----------------

- [ArrayReader](#arrayreader)
- [CsvReader](#csvreader)
- [ExcelReader](#excelreader)
- [FinderReader](#finderreader)
- [JsonFileReader](#jsonfilereader)
- [JsonReader](#jsonreader)
- [PdoStatementReader](#pdostatementreader)
- [Custom Readers](#custom-readers)
- [PHP 5.5 and Generators](#php-55-and-generators)
- [Reader Factory](#reader-factory)


ArrayReader
-----------

The `ArrayReader` feeds the elements of an array to the workflow. In addition to the methods required by
`ReaderInterface` it provides a `getData()` methods that returns the full array.

```php
use Plum\Plum\Reader\ArrayReader;

$reader = new ArrayReader(['Stark', 'Lannister', 'Targaryen', ...]);
$reader->getData(); // -> ['Stark', 'Lannister', 'Targaryen', ...]
```


CsvReader
---------

You can use the `CsvReader` to read data from a `.csv` file. You need to add the `plum-csv`
package to your project using Composer: `composer require plumphp/plum-csv:@stable`. Plum uses
[League\CSV](https://github.com/thephpleague/csv) to actually read the CSV files.

```php
use Plum\PlumCsv\CsvReader;

$reader = new CsvReader('countries.csv');
```

Optionally you can also pass the delimiter and enclosure to the constructor.

```php
$reader = new CsvReader('countries.csv`, ',', '"');
```

Most CSV files have a header row. Because Plum processes a CSV file row by row you need to add `HeaderConverter` to
change the index of each read item. In addition you can use the `SkipFirstFilter` to skip the header row.

```php
use Plum\Plum\Converter\HeaderConverter;
use Plum\Plum\Filter\SkipFirstFilter;

$workflow = new Workflow();
$workflow->addConverter(new HeaderConverter());
$workflow->addFilter(new SkipFirstFilter(1));
$reader = new CsvReader('countries.csv`, ',', '"');
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
implement the `getIterator()` method. In addition readers must implement a static `accepts()` method that returns
`true` if the reader can read the given resource (given to the constructor) and `false` if not. The method should
also return `false` if the reader does not have a constructor. The accepts method is used by
[ReaderFactory](#reader-factory).

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

    public static function accepts()
    {
        return false;
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


Reader Factory
--------------

Sometimes a workflow should be able to dynamically read from multiple readers depending on the input. Consider, for
example, a tool that reads either a CSV or an Excel file.

```php
use Plum\Plum\Reader\ReaderFactory;

$factory = new ReaderFactory();
$factory->add('Plum\PlumCsv\CsvReader')
        ->add('Plum\PlumExcel\ExcelReader');
$reader = $factory->create($inputFile);
```

In the above example `ReaderFactory` iterates through the readers and passes the `$inputFile` parameter to the static
`::accepts()` method of each reader. If the method returns `true`, the `->create()` creates the reader and returns it.
If no reader returns `true` then `->create()` returns `null`.

In practice things are often a little bit more complicated. Therefore you can pass an `accepts` and a `create` callback
to the `->add()` method in form of an options array as second argument. If you pass a `create` callback the class name
is not required and you can pass the options array to `->add()` as first argument.

To demonstrate we will expand the previous example and allow `.tsv` (Tab-separated values) files in addition to `.csv`
and `.xlsx` files. Additionally the values in `.csv` are separated by a semi-colon instead of a colon (which is the
default).

```php
use Plum\Plum\Reader\ReaderFactory;

$factory = new ReaderFactory();
// We don't need the class name if we provide both a `create` and a `accepts` callback.
$factory->add([
    'create'  => function ($input) { return new CsvReader($input, "\t"); },
    'accepts' => function ($input) { return substr($input, -4) === '.tsv'; }
]);
// We want to use a custom `create` callback to initalize CsvReader with `;`
$factory->add('Plum\PlumExcel\CsvReader', ['create' => function ($input) { return new CsvReader($input, ';'); }]);
// We use an `accepts` callback that returns `true` only for `.xlsx` files
$factory->add('Plum\PlumExcel\ExcelReader', [
    'accepts' => function ($input) { return substr($input, -5) === '.xlsx'; }
]);
$reader = $factory->create($inputFile);
```
