Writers
=======

Use writers to write the result of the workflow. The target doesn't necessarily have to write to a persistent storage,
you can also write, for example, into arrays or objects. Writers must implement the `WriterInterface` that provides
three methods: `writeItem()`, `prepare()` and `finish()`. The workflow calls `prepare()` before it reads the first item
and `finish()` after it read the last item.

Multiple writers can be added to a workflow in any ordering. Therefore it is possible to filter the read items, write
them somewhere, further filter them and then write them elsewhere.

ArrayWriter
-----------

The `ArrayWriter` writes the data into an array that can be retrieved using the `getData()` method.

```php
use Cocur\Plum\Writer\ArrayWriter;

$writer = new ArrayWriter();
// Workflow processing
$writer->getData() // -> [...]
```

ConsoleProgressWriter
---------------------

In a console application that uses Symfony's [Console]() component you can use `ConsoleProgressWriter` to give 
the user feedback on the progress of the workflow.

```php
use Cocur\Plum\Writer\ConsoleProgressWriter;

$worklow->addWriter(new ConsoleProgressWriter($progressBar));
$worklow->addWriter($otherWriter);
```

CsvWriter
---------

The `CsvWriter` allows you to write the data into a `.csv` file.

```php
use Cocur\Plum\Writer\CsvWriter;

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