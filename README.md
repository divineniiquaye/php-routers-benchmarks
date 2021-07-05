# The PHP Routers Benchmark

[![Software License](https://img.shields.io/badge/License-BSD--3-brightgreen.svg?style=flat-square)](LICENSE)
[![Workflow Status](https://img.shields.io/github/workflow/status/divineniiquaye/php-routers-benchmark/Tests?style=flat-square)](https://github.com/divineniiquaye/php-routers-benchmark/actions?query=workflow%3ATests)
[![Code Maintainability](https://img.shields.io/codeclimate/maintainability/divineniiquaye/php-routers-benchmark?style=flat-square)](https://codeclimate.com/github/divineniiquaye/php-routers-benchmark)
[![Quality Score](https://img.shields.io/scrutinizer/g/divineniiquaye/php-routers-benchmark.svg?style=flat-square)](https://scrutinizer-ci.com/g/divineniiquaye/php-routers-benchmark)
[![Sponsor development of this project](https://img.shields.io/badge/sponsor%20this%20package-%E2%9D%A4-ff69b4.svg?style=flat-square)](https://biurad.com/sponsor)

The intent here is to benchmark and also inventory all popular PHP routing solutions around.

## 📦 Installation & Basic Usage

This project requires [PHP] 7.4 or higher. The recommended way to install, is via [Composer] and [GitHub Ci](https://cli.github.com/) to clone the repo.:

```bash
$ gh repo clone divineniiquaye/php-routers-benchmark
$ cd php-routers-benchmark && composer install
$ php vendor/bin/phpbench run --report default
```

## 🧪 Benchmarking Process

The benchmarking process uses [phpbench](https://github.com/phpbench/phpbench). I can say of the benchmarks projects I've seen, my benchmark process is one of the best out there. With much reading and experimenting, I took inspiration from [Nikita Popov's](https://www.npopov.com/) blog post ["Fast request routing using regular expressions"](https://www.npopov.com/2014/02/18/Fast-request-routing-using-regular-expressions.html) and [Nicolas Grekas](https://nicolas-grekas.medium.com/) blog posts [Making Symfony’s Router 77.7x faster - 1/2](https://nicolas-grekas.medium.com/making-symfonys-router-77-7x-faster-1-2-958e3754f0e1), and [Making Symfony router lightning fast - 2/2 ](https://nicolas-grekas.medium.com/making-symfony-router-lightning-fast-2-2-19281dcd245b) in creating the benchmark process.

All router creates a total of about 1,600 unique routes, either up to, or lesser. With each having 400 for static paths, dynamic paths of 1 variable placeholders, a domain with static path, and a domain with dynamic path having 1 variable placeholder.

Benchmarks runs on three cases:

- **benchStaticRoutes**: match the best, average, worst, and invalid method route from the list of routing definitions. If domain is supported by router, matching is applied.
- **benchDynamicRoutes**: match the best, average, worst, and invalid method route from the list of routing definitions. If domain is supported by router, matching is applied.
- **benchOtherRoutes**: match other scenarios such as non existent route from routing definitions.

All the routers follows a set of [Parameterized Benchmarking](https://phpbench.readthedocs.io/en/latest/annotributes.html#id5):

- in the first set of benchmarking, routers are to match only a single(GET) HTTP method.
- in the second set of benchmarking, routers are to match all(GET,POST,PATCH,PUT,DELETE) HTTP methods.
- in the third and last set of benchmarking, routers are to match the first set including a domain if available.

Benchmarking were conducted on the following routers:

- [Flight Routing](https://github.com/divineniiquaye/flight-routing)
- [FastRoute](https://github.com/nikic/FastRoute)
- [Symfony](https://github.com/symfony/routing)
- [Laravel](https://github.com/illuminate/routing)
- [Aura3](https://github.com/auraphp/Aura.Router)
- [Rareloop](https://github.com/rareloop/router)
- [AltoRouter](https://github.com/altorouter/altorouter)
- [Nette](https://github.com/nette/routing)
- [Sunrise](https://github.com/sunrise-php/http-router)
- [SpiralRouter](https://github.com/spiral/router)


## ✳️ BenchMark Results

This is a benchmarked results runned on [GitHub Action] with Ubuntu 18, and PHP 7.4.

[PHP]: https://php.net
[Composer]: https://getcomposer.org
[GitHub Action]: https://github.com/divineniiquaye/php-routers-benchmark/runs/1867573092?check_suite_focus=true
