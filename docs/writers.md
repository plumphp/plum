<p align="center">
    <img src="http://cdn.florian.ec/plum-logo.svg" alt="Plum" width="300">
</p>

> Plum is a data processing pipeline that helps you to write structured, reusable and well tested data processing code.

Developed by [Florian Eckerstorfer](https://florian.ec) in Vienna, Europe.

---

Writers
=======

Use writers to write the result of the workflow. The target doesn't necessarily have to write to a persistent storage,
you can also write, for example, into arrays or objects. Writers must implement the `WriterInterface` that provides
three methods: `writeItem()`, `prepare()` and `finish()`. The workflow calls `prepare()` before it reads the first item
and `finish()` after it read the last item.

Multiple writers can be added to a workflow in any ordering. Therefore it is possible to filter the read items, write
them somewhere, further filter them and then write them elsewhere.


Table of Contents
-----------------

- [ArrayWriter](#arraywriter)
- [ConsoleProgressWriter](#consoleprogresswriter)
- [CsvWriter](#csvwriter)
- [ExcelWriter](#excelwriter)
- [JsonFileWriter](#jsonfilewriter)
- [JsonWriter](#jsonwriter)
- [Conditional Writers](#conditional-writers)


ArrayWriter
-----------

The `ArrayWriter` writes the data into an array that can be retrieved using the `getData()` method.

```php
use Plum\Plum\Writer\ArrayWriter;

$writer = new ArrayWriter();
// Workflow processing
$writer->getData() // -> [...]
```


ConsoleProgressWriter
---------------------

In a console application that uses the Symfony [Console](http://symfony.com/doc/current/components/console/index.html)
component you can use `ConsoleProgressWriter` to give the user feedback on the progress of the workflow. You need to
install the `plum-console` package to use it: `composer require plumphp-plum-console@stable`.

```php
use Plum\PlumConsole\ConsoleProgressWriter;

$worklow->addWriter(new ConsoleProgressWriter($progressBar));
$worklow->addWriter($otherWriter);
```


CsvWriter
---------

The `CsvWriter` allows you to write the data into a `.csv` file. You need to install the `plum-csv` package to use it:
`composer require plumphp/plum-csv@dev-master`.

```php
use Plum\PlumCsv\CsvWriter;

$writer = new CsvWriter('foobar.csv', ',', '"');
$writer->prepare();
$writer->writeItem(['value 1', 'value 2', 'value 3');
$writer->finish();
```

The second and third argument of `__construct()` are optional and by default `,` and `"` respectively. In addition
the `setHeader()` method can be used to define the names of the columns. It has to be called before the `prepare()`.

```php
$writer = new CsvWriter('foobar.csv');
$writer->setHeader(['column 1', 'column 2', 'column 3']);
$writer->prepare();
```

When you read data dynamically you probably don't want to set the header columns manually. You can call 
`autoDetectHeader()` to use the array keys of the first item written to `CsvWriter` as headers.

```php
$writer = new CsvWriter('foobar.csv');
$writer->autoDetectHeader(); // Must be called before the first `writeItem()`
```


ExcelWriter
-----------

The `ExcelWriter` allows you to write the data into a Microsoft Excel (`.xlsx` and `.xls`) file. You need to install the
`plum-excel` package to use it: `composer require plumphp/plum-excel@stable`.

```php
$writer = new ExcelWriter('cities.xlsx');
$writer->autoDetectHeader();
$writer->prepare();
$writer->writeItem(['Town' => 'Vienna', 'Country' => 'Austria']);
$writer->writeItem(['Town' => 'Hamburg', 'Country' => 'Germany']);
$writer->finish();
```

The call to `autoDetectHeader()` will cause `ExcelWriter` to add a header column as row using the keys from the first
written item.

Instead of only passing the filename to the constructor, you can also directly pass a `PHPExcel` object. 

```php
$writer = new ExcelWriter('cities.xlsx', new PHPExcel());
```


JsonFileWriter
--------------

`JsonFileWriter` writes the items as JSON into a file.  You need add the `plum-json` package to your project using
Composer: `composer require plumphp/plum-json:@stable`.

```php
use Plum\PlumJson\JsonFileWriter;

$writer = new JsonFileWriter('foobar.json');
$writer->writeItem(['key1' => 'value1', 'key2' => 'value2'));
$writer->finish();
```

It is essential that `finish()` is called, because there happens the actual writing. The `prepare()` method does
nothing.

JsonWriter
----------

`JsonWriter` converts the items into JSON format. Please checkout [JsonFileWriter](#jsonfilewriter) if you want to
write the JSON into a file. You need add the `plum-json` package to your project using Composer:
`composer require plumphp/plum-json:@stable`.

```php
use Plum\PlumJson\JsonWriter;

$writer = new JsonWriter();
$writer->writeItem(['key1' => 'value1', 'key2' => 'value2'));
echo $writer->getJson(); // [{'key1': 'value1', 'key2': 'value2'}]
```

It is essential that `finish()` is called, because there happens the actual writing. The `prepare()` method does
nothing.


Conditional Writers
-------------------

Writers can be conditional if a filter is passed to the `addWriter()` method.

```php
$workflow->addWriter($writer, $filter);
```

An item is only written to a conditional writer, if the filter returns `true` for the item.

---

<p align="right">
    Continue with <a href="filters.md">Filters</a>
</p>
