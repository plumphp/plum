Converters
==========

Converters take an item and convert it into something else. They must implement `ConverterInterface` which has a single
`convert()` method.


Table of Contents
-----------------

- [CallbackConverter](#callbackconverter)
- [FileGetContentsConverter](#filegetcontentsconverter)
- [Custom Converters](#custom-converters)
- [Value Converters](#value-converters)


CallbackConverter
-----------------

The `CallbackConverter` calls a callback to convert a given item.

```php
use Plum\Plum\Converter\CallbackConverter;

$converter = new CallbackConverter(function ($item) { return strtoupper($item); });
$converter->convert('foo'); // -> FOO
```


FileGetContentsConverter
------------------------

The `FileGetContentsConverter` takes a `SplFileInfo` object or a filename and returns both the `SplFileInfo` object
and the contents of the file. It is part of `plum-file`.

```php
use Plum\PlumFile\FileGetContentsConverter;

$converter = new FileGetContentsConverter();
$converter->convert('foo.txt'); // -> ['file' => \SplFileInfo Object, 'content' => '...']
```


Custom Converters
-----------------

You can add custom converters by creating a class that implements `Plum\Plum\Converter\ConverterInterface`.

```php
use Plum\Plum\Converter\ConverterInterface;

class Rot13Converter implements ConverterInterface
{
    public function convert($item)
    {
        return str_rot13($item);
    }
}
```


Value Converters
----------------

Often you don't want to convert the whole item, but only a single field and Plum therefore supports value converters.
Plum uses Vale to retrieve a field from an arbitrary array or object, apply a converter and stores the converted
value back into its original position.

```php
$writer = new ArrayWriter();
$workflow = new Workflow();
$workflow->addValueConverter(['foo', 'bar', 'qoo', 'qoz'], new CallbackConverter(function ($v) { return $v*2; }));
$workflow->addWriter($writer);
$workflow->process(new ArrayReader([['foo' => ['bar' => ['qoo' => ['qoz' => 21]]]]]));

$writer->getData(); // -> [['foo' => ['bar' => ['qoo' => ['qoz' => 42]]]]]
```

Value converters also support filters and you can prepend them to the workflow.
