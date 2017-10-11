# prmrgt-monolog-formatter
A [custom Monolog formatter](https://github.com/proemergotech/prmrgt-monolog-formatter) for extracting custom key-values from log context. Uses RFC3339/ISO8601 datetime with microseconds by default.

## Installation
* Install via Composer
```sh
composer require proemergotech/prmrgt-monolog-formatter
```
## Usage
This formatter can be added to a Monolog handler as any other formatter. Each logging handler uses a Formatter to format the record before logging it.
```php
$handler = new Monolog\Handler\StreamHandler(env('LOG_STREAM', '/tmp/stdout.sock'), \Monolog\Logger::DEBUG);
$handler->setFormatter(new PrmrgtLogFormatter(['keyToExtract', 'otherKeyToExract], 'Y-m-d H:i:s'));
$monolog->pushHandler($handler);
```
You can find more information on handlers, formatters and processors in the official [Monolog documentation](https://github.com/Seldaek/monolog/blob/master/doc/02-handlers-formatters-processors.md).
## Contributing
See CONTRIBUTING.md file.
## Credits
This package is developed by [Miklós Boros](https://github.com/cherubmiki/) at [Pro Emergotech Ltd.](https://github.com/proemergotech/).
## License
This project is released under the [MIT License](http://www.opensource.org/licenses/MIT).