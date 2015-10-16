<p align="center">
    <img src="http://cdn.florian.ec/plum-logo.svg" alt="Plum" width="300">
</p>

> Plum is a data processing pipeline that helps you to write structured, reusable and well tested data processing code.

Developed by [Florian Eckerstorfer](https://florian.ec) in Vienna, Europe.

---

Converters take an item and convert it into something else. They must implement `ConverterInterface` which has a single
`convert()` method.


Table of Contents
-----------------

- [CallbackConverter](#callbackconverter)
- [MappingConverter](#mappingconverter)
- [FileGetContentsConverter](#filegetcontentsconverter)
- [MarkdownConverter](#markdownconverter)
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


MappingConverter
----------------

The `MappingConverter` can be used to maps a value from one element to another. The converter uses
[Vale](https://github.com/cocur/vale) to target elements in complex, nested data structures (objects and arrays); the
given item therefore can be even a complex object. Vale tries a lot of different variants to access an element, thus
it should be possible to transform nearly every item.

```php
use Plum\Plum\Converter\MappingConverter;

$converter = new MappingConverter();
$converter->addMapping(['foo'], ['bar']);
$converter->convert(['foo' => 'foobar']); // -> ['bar' => 'foobar']
```

If two or more mappings which point to the same target element are given, the last one added to the converter takes
precedence and the first element will be lost.

```php
$converter = new MappingConverter();
$converter->addMapping(['foo'], ['bar']);
$converter->addMapping(['qoo'], ['bar']);
$converter->convert(['foo' => 'foobar', 'qoo' => 'qoobar']); // -> ['bar' => 'qoobar']
```

If the `from` mapping is empty, the converter creates a new array and sets the item as `to` element in that array:

```php
$converter->addMappiong('', 'bar');
$converter->convert('foobar'); // -> ['bar' => 'foobar']
```

In contrast, if the `to` mapping is empty the `from` value will returned as item.
 
```php
$converter->addMapping('bar', '');
$converter->convert(['bar' => 'foobar'); // -> 'foobar'
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


MarkdownConverter
------------------------

The `MarkdownConverter` converts Markdown into HTML. It is part of `plum-markdown`.

```php
use Plum\PlumMarkdown\MarkdownConverter;

$converter = new MarkdownConverter();
$converter->convert('*foobar*'); // -> "<p><em>foobar</em></p>
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
Plum uses [Vale](https://github.com/cocur/vale) to retrieve a field from an arbitrary array or object, apply a converter
and stores the converted value back into its original position.

```php
$writer = new ArrayWriter();
$workflow = new Workflow();
$workflow->addValueConverter(new CallbackConverter(function ($v) { return $v*2; }), ['foo', 'bar', 'qoo', 'qoz']);
$workflow->addWriter($writer);
$workflow->process(new ArrayReader([['foo' => ['bar' => ['qoo' => ['qoz' => 21]]]]]));

$writer->getData(); // -> [['foo' => ['bar' => ['qoo' => ['qoz' => 42]]]]]
```

Value converters also support filters and you can prepend them to the workflow.
