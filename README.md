# Street Group Interview Tech Test
This interview tech test loads CSV data of Homeowners' names and makes the data available in the consistent format of serializable data object classes.

## Requirements

This implementation uses PHP features that are available in PHP 7.4.0 and later.

While it doesn't use any external dependencies, it does use modern PSR-4 autoloading, so you will need to have Composer installed.

## Installation

1. Clone the repository
2. Run `composer install` to install the class autoloading

## Usage

### CLI

To use this functionality as a CLI tool, run the following command:

```bash
php ./src/cli.php <optional-csv-file-path>
```

If you don't provide a CSV file path, the script will default to using the provided `./data/examples-4-.csv` file.

### Web

To use this functionality as a web tool, run the following command:

```bash
php -S localhost:8000 -t ./web
```

Then, navigate to `http://localhost:8000` in your web browser.

