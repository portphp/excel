# portphp/excel

> **DEPRECATED.** This package depends on the abandoned [PHPExcel](https://github.com/PHPOffice/PHPExcel) library and is no longer maintained.
>
> Use **[portphp/spreadsheet](https://github.com/portphp/spreadsheet)** (PhpSpreadsheet) instead:
>
> ```bash
> composer require portphp/spreadsheet
> ```

[![Latest Version](https://img.shields.io/github/release/portphp/excel.svg?style=flat-square)](https://github.com/portphp/excel/releases)
[![PHP Version](https://img.shields.io/packagist/php-v/portphp/excel.svg?style=flat-square)](https://packagist.org/packages/portphp/excel)

Excel reader and writer for [Port](https://github.com/portphp) based on PHPExcel.

## Migration

| Old (`portphp/excel`) | New (`portphp/spreadsheet`) |
|----------------------|-----------------------------|
| `Port\Excel\ExcelReader` | `Port\Spreadsheet\SpreadsheetReader` |
| `Port\Excel\ExcelWriter` | `Port\Spreadsheet\SpreadsheetWriter` |
| `phpoffice/phpexcel` | `phpoffice/phpspreadsheet` |

Namespace and class names differ; APIs are intentionally similar for easy migration.

## Installation (not recommended)

```bash
$ composer require portphp/excel
```

## Documentation

Historical docs: https://portphp.readthedocs.io. Prefer the spreadsheet package docs going forward.

## Issues and feature requests

Please report issues and request features at https://github.com/portphp/portphp/issues.

## License

This package is licensed under the [MIT license](LICENSE).
