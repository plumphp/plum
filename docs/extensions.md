<p align="center">
    <img src="http://cdn.florian.ec/plum-logo.svg" alt="Plum" width="300">
</p>

> Plum is a data processing pipeline that helps you to write structured, reusable and well tested data processing code.

---

<p align="center">
    <a href="index.md">Index</a>
    <a href="workflow.md">Workflow</a>
    <a href="readers.md">Readers</a>
    <a href="writers.md">Writers</a>
    <a href="filters.md">Filters</a>
    <a href="converters.md">Converters</a>
    <strong>Extensions</strong>
</p>

---

The true power of Plum comes from its extensibility. On this page we list all currently available packages for Plum
as well as libraries that provide Plum integration.


Table of Contents
-----------------

1. [Additional Packages](#additonal-packages)
2. [Integrations](#integrations)
3. [Missing Packages or Integrations?](#missing-packages-or-integrations)


Additional packages
-------------------

The core of Plum (the `plumphp/plum` package) contains only the essential classes. However, we provide additional
packages which give you more functionality out of the box:

- [Console](#console)
- [Data Types](#data-types)
- [Database](#database)
- [Dates](#dates)
- [File Formats](#file-formats)
- [Filesystem](#filesystem)
- [Templating](#templating)

### Console

- [**plum-console**](https://github.com/plumphp/plum-console): Integration for the Symfony Console component.

### Data Types

- [**plum-collection**](https://github.com/plumphp/plum-collection): Integration for [Collection](https://github.com/cocur/collection).

### Database

- [**plum-doctrine**](https://github.com/plumphp/plum-doctrine): Integration for Doctrine.
- [**plum-pdo**](https://github.com/plumphp/plum-pdo): Integration for PDO.

### Dates

- [**plum-date**](https://github.com/plumphp/plum-date): Converters and filters for working with date and time.

### File Formats

- [**plum-csv**](https://github.com/plumphp/plum-csv): Readers and writers for CSV files.
- [**plum-excel**](https://github.com/plumphp/plum-excel): Readers and writers for Microsoft Excel files.
- [**plum-json**](https://github.com/plumphp/plum-json): Readers and writers for JSON strings and files.
- [**plum-markdown**](https://github.com/plumphp/plum-markdown): Markdown converter.
- [**plum-yaml**](https://github.com/plumphp/plum-yaml): Dump and parse YAML files.

### Filesystem

- [**plum-file**](https://github.com/plumphp/plum-file): Converters and filters for working with files.
- [**plum-finder**](https://github.com/plumphp/plum-finder): Integration for the Symfony Finder component.

### Templating

- [**plum-twig**](https://github.com/plumphp/plum-twig): Render items using Twig templates.


Integrations
------------

Additionally there exists libraries with Plum integrations built in:

- [**Slugify**](https://github.com/cocur/slugify): Converts a string to a slug.
- [**Arff**](https://github.com/cocur/arff): Writes `.arff` files, required for Weka.


Missing Packages or Integrations?
---------------------------------

If you have integrated Plum into your library or software or have created an Plum package not listed on this page
please submit a **pull request** for this page. Thank you very much.

---

<p align="center">
    <a href="index.md">Index</a>
    <a href="workflow.md">Workflow</a>
    <a href="readers.md">Readers</a>
    <a href="writers.md">Writers</a>
    <a href="filters.md">Filters</a>
    <a href="converters.md">Converters</a>
    <strong>Extensions</strong>
</p>
