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
