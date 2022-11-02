# graphp/algorithms

[![CI status](https://github.com/graphp/algorithms/workflows/CI/badge.svg)](https://github.com/graphp/algorithms/actions)

Common mathematical graph algorithms implemented in PHP

> **Development version:** This branch contains the code for the upcoming
> version 0.9. For the code of the current version 0.8, check out the
> [`0.8.x` branch](https://github.com/graphp/algorithms/tree/0.8.x).
>
> The upcoming version 0.9 will be the way forward for this package. However,
> we will still actively support version 0.8 for those not yet on the latest
> version. See also [installation instructions](#install) for more details.

## Install

The recommended way to install this library is [through Composer](https://getcomposer.org/).
[New to Composer?](https://getcomposer.org/doc/00-intro.md)

Once released, this project will follow [SemVer](https://semver.org/).
At the moment, this will install the latest development version:

```bash
composer require graphp/algorithms:^0.9@dev
```

See also the [CHANGELOG](CHANGELOG.md) for details about version upgrades.

This project aims to run on any platform and thus does not require any PHP
extensions and supports running on legacy PHP 5.3 through current PHP 7+.
It's *highly recommended to use PHP 7+* for this project.

## Tests

To run the test suite, you first need to clone this repo and then install all
dependencies [through Composer](https://getcomposer.org/):

```bash
composer install
```

To run the test suite, go to the project root and run:

```bash
vendor/bin/phpunit
```

## License

This project is released under the permissive [MIT license](LICENSE).
