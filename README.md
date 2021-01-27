# graphp/algorithms

[![CI status](https://github.com/graphp/algorithms/workflows/CI/badge.svg)](https://github.com/graphp/algorithms/actions)

Common mathematical graph algorithms implemented in PHP

>   You're viewing the contents of the `master` development brach. Note that this
    branch is subject to active development and will contain breaking changes
    for the upcoming release. If you want to use the latest release version,
    see also the `v0.8.x` release branch for more details.

>   Note: This project is in beta stage! Feel free to report any issues you encounter.

## Install

The recommended way to install this library is [through Composer](https://getcomposer.org).
[New to Composer?](https://getcomposer.org/doc/00-intro.md)

This will install the latest supported version:

```bash
$ composer require graphp/algorithms:^0.8.2
```

See also the [CHANGELOG](CHANGELOG.md) for details about version upgrades.

This project aims to run on any platform and thus does not require any PHP
extensions and supports running on legacy PHP 5.3 through current PHP 7+ and
HHVM.
It's *highly recommended to use PHP 7+* for this project.

## Tests

To run the test suite, you first need to clone this repo and then install all
dependencies [through Composer](https://getcomposer.org):

```bash
$ composer install
```

To run the test suite, go to the project root and run:

```bash
$ php vendor/bin/phpunit
```

## License

Released under the terms of the permissive [MIT license](http://opensource.org/licenses/MIT).
