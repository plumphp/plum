Change Log
==========

Version 0.4 (17 October 2015)
-----------------------------

- [#8](https://github.com/plumphp/plum/pull/8) Remove `Workflow::addValueConverter()` and `Workflow::addValueFilter()`
and handle value filters and converter by `::addConverter()` and `::addFilter()`.
- [#9](https://github.com/plumphp/plum/pull/9) Allowing processing of multiple readers.

Version 0.3.1 (15 May 2015)
---------------------------

- `MappingConverter` allows to copy instead of move values
- Conditional converters can now define the field the filter should be applied on

Version 0.3 (28 April 2015)
---------------------------

- Refactored `Workflow`
- Improved adding of filters, converters and writers
- Allow callback to be passed directly to filters and converters
- Improved `MappingConverter`

Version 0.2 (21 April 2015)
---------------------------

- Add value converters and value filters
- Add `MappingConverter`
- Add `LogConverter`
- Filter items if converter returns `null`

Version 0.1 (18 March 2015)
---------------------------

- Initial release
