# ScraperStore

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A scraper that copies content from online store.

## Install

Via Composer

First install [Goutte](https://github.com/FriendsOfPHP/Goutte)

``` bash
$ composer require fajardm/ScraperStore
```

## Usage

``` php
$scrape = new Scrapper();
print_r($scrape->store("matahari_mall", "https://www.mataharimall.com/p-2/handphone?page=1&per_page=25&fq=brand_name:samsung"));
```

Parameter :
Matahari Mall = matahari_mall
Bhineka = bhineka
Elevenia = elevenia

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email :author_email instead of using the issue tracker.

## Credits

- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/fajardm/scraperstore.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/fajardm/scraperstore/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/fajardm/scraperstore.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/fajardm/scraperstore.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/fajardm/scraperstore.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/fajardm/scraperstore
[link-travis]: https://travis-ci.org/fajardm/scraperstore
[link-scrutinizer]: https://scrutinizer-ci.com/g/fajardm/scraperstore/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/fajardm/scraperstore
[link-downloads]: https://packagist.org/packages/fajardm/scraperstore
[link-author]: https://github.com/fajardm
[link-contributors]: ../../contributors
