APM-PHP
=======

A tool to index profiling data to different sources.

This php package is inspired in [twlogger](https://github.com/nishantsaini/twlogger) which was used a base to implement 
this package.

## Requirements

   * [Tideways](https://github.com/tideways/php-profiler-extension) to actually profile the data.

## Installation

`composer require ernestobaezf/laravel-apm-php`

## Configuration

Coming

## Use case

Coming 

## Profile a CLI Script

The simplest way to profile a CLI is to use
`external/header.php`. `external/header.php` is designed to be combined with PHP's
[auto_prepend_file](http://www.php.net/manual/en/ini.core.php#ini.auto-prepend-file)
directive. You can enable `auto_prepend_file` system-wide
through `php.ini`. Alternatively,
you can enable include the `header.php` at the top of your script:

```php
<?php
require '/path/to/TwLogger/external/header.php';
// Rest of script.
```

You can alternatively use the `-d` flag when running php:

```bash
php -d auto_prepend_file=/path/to/TwLogger/external/header.php do_work.php
```

## Saving & Importing Profiles

Be aware of file locking: depending on your workload, you may need to
change the `save.handler.filename` file path to avoid file locking
during the import.

The following demonstrate the use of `external/import.php`:

```bash
php external/import.php -f /path/to/file
```

**Warning**: Importing the same file twice will index twice, resulting in duplicate profiles

## License

Permission is hereby granted, free of charge, to any person obtaining a
copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be included
in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

