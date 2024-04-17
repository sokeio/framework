# Sokeio framework

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sokeio/framework.svg?style=flat-square)](https://packagist.org/packages/sokeio/framework)
[![Total Downloads](https://img.shields.io/packagist/dt/sokeio/framework.svg?style=flat-square)](https://packagist.org/packages/sokeio/framework)


**Sokeio** is a Laravel-based web application development package that provides support for developing modules, themes, livewire, and shortcodes. With Sokeio, users can develop web applications faster and easier than ever before. The package provides flexible features and utilities to help users optimize the development process and accelerate the development speed of their web applications.

## Quick tutorial
* Make an Administrator page in 5 minutes: 

```bash
$ composer require sokeio/framework
```

```bash
$ php artisan migrate
```

* Create user admin or reset password for account supper-admin

set .env

```bash
SOKEIO_MAKE_USER_ADMIN=true
```

```bash
$ php artisan so:make-user-admin --e "admin@sokeio.com" --f "Nguyen Van Hau"
```

## Template boilerplate 
Link [https://github.com/sokeio/sokeio](https://github.com/sokeio/sokeio)

## Feature(New):
- Modules
- Themes
- Actions
- ShortCodes

## Technology used:
- Laravel 11x
- Livewire 3x
- Boostrap 5x
- Tabler and Tabler-icon


## Requirements

PHP 8.2+

## Installation

You can install package via composer

```bash
$ composer require sokeio/framework
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

Make theme:

```bash
$ php artisan so:make-theme Demo3 -a true -t theme -f true
```

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## Product list:

[Free Online Tutorials](https://tutorialaz.com/)

[Discover The Best AI Websites & Tools](https://hau.xyz/)

## License

[MIT](./LICENSE)
