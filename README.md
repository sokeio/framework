# Sokeio framework

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sokeio/framework.svg?style=flat-square)](https://packagist.org/packages/sokeio/framework)
[![Total Downloads](https://img.shields.io/packagist/dt/sokeio/framework.svg?style=flat-square)](https://packagist.org/packages/sokeio/framework)


**Sokeio** is a Laravel-based web application development package that provides support for developing modules, plugins, themes, livewire, and shortcodes. With Sokeio, users can develop web applications faster and easier than ever before. The package provides flexible features and utilities to help users optimize the development process and accelerate the development speed of their web applications.

Make an Administrator page in 5 minutes: 

```bash
$ composer require sokeio/framework
```


**Template boilerplate**: https://github.com/ByteAsia/laravel-boilerplate
## Feature(New):
- Modules
- Themes
- Plugins
- Actions
- ShortCodes

## Technology used:
- Laravel 10x
- Livewire 3x
- Boostrap 5x
- Tabler and Tabler-icon


## Requirements

PHP 8.2+

## Installation

You can install package via composer

```bash
$ composer require sokeio/framework
$ php artisan vendor:publish --tag=lfm_config
$ php artisan vendor:publish --tag=lfm_public
```

```bash
account: admin@hau.xyz
password: AdMin@123

```

## Usage

Make module:

```bash
$ php artisan so:make-module Demo3 -a true -f true
```

Make plugin:

```bash
$ php artisan so:make-plugin Demo3 -a true -f true
```

Make theme:

```bash
$ php artisan so:make-theme Demo3 -a true -t theme -f true
```

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## Product list:

[Free Online Tutorials](https://tutorialaz.com/)

## License

[MIT](./LICENSE)
