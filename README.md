# The PHP Routers Benchmark

[![Software License](https://img.shields.io/badge/License-BSD--3-brightgreen.svg?style=flat-square)](LICENSE)
[![Workflow Status](https://img.shields.io/github/workflow/status/divineniiquaye/php-routers-benchmark/Tests?style=flat-square)](https://github.com/divineniiquaye/php-routers-benchmark/actions?query=workflow%3ATests)
[![Code Maintainability](https://img.shields.io/codeclimate/maintainability/divineniiquaye/php-routers-benchmark?style=flat-square)](https://codeclimate.com/github/divineniiquaye/php-routers-benchmark)
[![Quality Score](https://img.shields.io/scrutinizer/g/divineniiquaye/php-routers-benchmark.svg?style=flat-square)](https://scrutinizer-ci.com/g/divineniiquaye/php-routers-benchmark)
[![Sponsor development of this project](https://img.shields.io/badge/sponsor%20this%20package-%E2%9D%A4-ff69b4.svg?style=flat-square)](https://biurad.com/sponsor)

The intent here is to benchmark and also inventory all popular PHP routing solutions around.

## 📦 Installation & Basic Usage

This project requires [PHP] 7.4 or higher. The recommended way to install, is via [Composer]. Simply run:

```bash
$ composer require divineniiquaye/php-routers-benchmark
```

## 🧪 Benchmarking Process

The current test creates 100 unique routes with 3 variables placeholder each.

Example of route: `/controller1/action1/{world}`

This benchmarking will be runned on the following three different situations for both path & subdomain:

- the best case (i.e when a request matches the first route for all differents HTTP method)
- the worst case (i.e when a request matches the last route for all differents HTTP method)
- the average case (i.e the mean which is probably the most realistic test).

And all tests will be runned using the following sets of routes:

- in the first set all routes matches all HTTP methods.
- in the second set all routes matches only a single HTTP method.

The benchmarked routing implementations are:

- [Flight Routing](https://github.com/divineniiquaye/flight-routing)
- [FastRoute](https://github.com/nikic/FastRoute)
- [Symfony](https://github.com/symfony/routing)
- [Laravel](https://github.com/illuminate/routing)
- [Aura3](https://github.com/auraphp/Aura.Router)
- [Pecee](https://github.com/pecee/simple-router)
- [Rareloop](https://github.com/rareloop/router)
- [AltoRouter](https://github.com/altorouter/altorouter)
- [Nette](ht##tps://github.com/nette/routing)


## ✳️ BenchMark Results


[PHP]: https://php.net
[Composer]: https://getcomposer.org
