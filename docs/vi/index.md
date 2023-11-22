# Sokeio

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sokeio/sokeio.svg?style=flat-square)](https://packagist.org/packages/sokeio/sokeio)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/sokeio/sokeio/run-tests?label=Laravel)](https://github.com/sokeio/sokeio/actions?query=workflow%3ALaravel+)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/sokeio/sokeio/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/sokeio/sokeio/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/sokeio/sokeio.svg?style=flat-square)](https://packagist.org/packages/sokeio/sokeio)

**Sokeio** là một gói phát triển ứng dụng web dựa trên Laravel, cung cấp hỗ trợ cho việc phát triển các Module, Plugin, Theme, Livewire và shortcode. Với Sokeio, người dùng có thể phát triển ứng dụng web nhanh hơn và dễ dàng hơn bao giờ hết. Gói phát triển cung cấp các tính năng linh hoạt và tiện ích giúp người dùng tối ưu quy trình phát triển và tăng tốc độ phát triển của ứng dụng web của họ.

## Yêu cầu

PHP 8.2+

## Cài đặt

Bạn có thể cài đặt gói bằng Composer bằng cách thực hiện các bước sau:

```bash
$ composer require sokeio/sokeio
$ php artisan migrate
$ php artisan b:setup
$ php artisan vendor:publish --tag=lfm_config
$ php artisan vendor:publish --tag=lfm_public
```

Tài khoản mặc định

```bash
account: admin@hau.xyz
password: AdMin@123

```

## Sử dụng nhanh

Tạo 1 module mới bằng lệnh:

```bash
$ php artisan mb:module Demo3 -a true -f true
```

Tạo 1 plugin mới bằng lệnh:

```bash
$ php artisan mb:plugin Demo3 -a true -f true
```

Tạo 1 theme mới bằng lệnh:

```bash
$ php artisan mb:theme Demo3 -a true -f true
```

# Hướng dẫn sử dụng các tính năng dưới dây:

* [Tạo và sử dụng theme](./theme.md)
* [Tạo và sử dụng module](./module.md)
* [Tạo và sử dụng plugin](./plugin.md)

# Các tính năng chung cho cả module, plugin và theme:

* [Tính năng Action](./action.md)
* [Tính năng command](./command.md)
* [Tính năng common](./common.md)
* [Tính năng crud](./crud.md)
* [Tính năng field](./field.md)
* [Tính năng locale](./locale.md)
* [Tính năng setting](./setting.md)
* [Tính năng shortcode](./shortcode.md)
* [Tính năng widget](./widget.md)

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License

[MIT](./LICENSE)
