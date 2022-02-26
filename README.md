<br>
<img src="https://www.insight-media.be/images/logo.svg" height="80">

# A console command to keep your local Statamic project in sync with the production version.

[![Latest Version on Packagist](https://img.shields.io/badge/packagist-v1.0.0-blue)](https://packagist.org/p2/insight-media/statamic-sync)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/insight-media/statamic-sync/Check%20&%20fix%20styling?label=code%20style)](https://github.com/insight-media/statamic-sync/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/insight-media/statamic-sync.svg?style=flat-square)](https://packagist.org/packages/insight-media/statamic-sync)

This package for Statamic projects provides a console command to pull the CMS content from your production version into your local version.

The package requires an SSH connection to your webserver.

## Installation

You can install the package via composer:

```bash
composer require --dev insight-media/statamic-sync
```

You can optionally publish the config file with:

```bash
php artisan vendor:publish --tag="statamic-sync-config"
```

Add and edit the following env variables:

```code
SSH_USER=user
SSH_HOST=yourproject.com
SSH_PORT=22
SSH_PATH=/var/www/project
```

## Usage

```bash
php artisan statamic:sync
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Insight Media](https://github.com/insight-media)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
