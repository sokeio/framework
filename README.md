# BytePlatform

[![Latest Version on Packagist](https://img.shields.io/packagist/v/byteplatform/byteplatform.svg?style=flat-square)](https://packagist.org/packages/byteplatform/byteplatform)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/byteplatform/byteplatform/run-tests?label=Laravel)](https://github.com/byteplatform/byteplatform/actions?query=workflow%3ALaravel+)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/byteplatform/byteplatform/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/byteplatform/byteplatform/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/byteplatform/byteplatform.svg?style=flat-square)](https://packagist.org/packages/byteplatform/byteplatform)


**BytePlatform** is a Laravel-based web application development package that provides support for developing modules, plugins, themes, livewire, and shortcodes. With BytePlatform, users can develop web applications faster and easier than ever before. The package provides flexible features and utilities to help users optimize the development process and accelerate the development speed of their web applications.

Make an Administrator page in 5 minutes: 

```bash
$ composer require byteplatform/admin
```


**Template boilerplate**: https://github.com/ByTechAsia/laravel-boilerplate
## Feature(New):
- Modules
- Themes
- Plugins
- Actions
- ShortCodes
- CURD Base
- Auth(User,Role,Permission)
- Setting

## Technology used:
- Laravel 10x
- Livewire 3x
- unisharp/laravel-filemanager 2.6
- staudenmeir/eloquent-eager-limit 1.8.3
- Boostrap 5x
- Tabler and Tabler-icon


## Requirements

PHP 8.2+

## Installation

You can install package via composer

```bash
$ composer require byteplatform/byteplatform
$ php artisan migrate
$ php artisan b:setup
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
$ php artisan mb:module Demo3 -a true -f true
```

Make plugin:

```bash
$ php artisan mb:plugin Demo3 -a true -f true
```

Make theme:

```bash
$ php artisan mb:theme Demo3 -a true -t theme -f true
```

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## Product list:

[Free Online Tutorials](https://tutorialaz.com/)

## License

[MIT](./LICENSE)
