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

In a console application that uses Symfony's [Console]() component you can use `ConsoleProgressWriter` to give 
the user feedback on the progress of the workflow.

```php
use Plum\Plum\Writer\ConsoleProgressWriter;

$worklow->addWriter(new ConsoleProgressWriter($progressBar));
$worklow->addWriter($otherWriter);
```


CsvWriter
---------

The `CsvWriter` allows you to write the data into a `.csv` file.

```php
use Plum\Plum\Writer\CsvWriter;

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
