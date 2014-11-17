Converters
==========

Converters take an item and convert it into something else. They must implement `ConverterInterface` which has a single
`convert()` method.


Table of Contents
-----------------

- [CallbackConverter](#callbackconverter)
- [FileGetContentsConverter](#filegetcontentsconverter)
- [Custom Converters](#custom-converters)


CallbackConverter
-----------------

The `CallbackConverter` calls a callback to convert a given item.

```php
use Cocur\Plum\Converter\CallbackConverter;

$converter = new CallbackConverter(function ($item) { return strtoupper($item); });
$converter->convert('foo'); // -> FOO
```


FileGetContentsConverter
------------------------

The `FileGetContentsConverter` takes a `SplFileInfo` object or a filename and returns both the `SplFileInfo` object
and the contents of the file.

```php
use Cocur\Plum\Converter\FileGetContentsConverter;

$converter = new FileGetContentsConverter();
$converter->convert('foo.txt'); // -> ['file' => \SplFileInfo Object, 'content' => '...']
```


Custom Converters
-----------------

You can add custom converters by creating a class that implements `Cocur\Plum\Converter\ConverterInterface`.

```php
use Cocur\Plum\Converter\ConverterInterface;

class Rot13Converter implements ConverterInterface
{
    public function convert($item)
    {
        return str_rot13($item);
    }
}
```
