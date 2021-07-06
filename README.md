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
- [AltoRouter](https://github.com/altorouter/altorouter)
- [Nette](https://github.com/nette/routing)
- [Sunrise](https://github.com/sunrise-php/http-router)
- [SpiralRouter](https://github.com/spiral/router)


## ✳️ BenchMark Results

This is a benchmarked results runned on [GitHub Action] with Ubuntu 18, and PHP 7.4.

```
AltRouter
+--------------------+----------------------------+----------+-------------+-------------+-------------+-------------+-----------+--------+
| subject            | set                        | mem_peak | mode        | best        | mean        | worst       | stdev     | rstdev |
+--------------------+----------------------------+----------+-------------+-------------+-------------+-------------+-----------+--------+
| benchStaticRoutes  | GET Method,Best Case       | 3.821mb  | 2.389μs     | 2.260μs     | 2.356μs     | 2.430μs     | 0.065μs   | ±2.75% |
| benchStaticRoutes  | ALL Methods,Best Case      | 3.823mb  | 10.865μs    | 10.770μs    | 10.948μs    | 11.210μs    | 0.167μs   | ±1.52% |
| benchStaticRoutes  | GET Method,Average Case    | 3.821mb  | 923.173μs   | 879.470μs   | 913.298μs   | 955.430μs   | 29.595μs  | ±3.24% |
| benchStaticRoutes  | ALL Methods,Average Case   | 3.823mb  | 4,639.312μs | 4,463.200μs | 4,613.920μs | 4,724.050μs | 84.770μs  | ±1.84% |
| benchStaticRoutes  | GET Method,Worst Case      | 3.821mb  | 1,773.426μs | 1,689.640μs | 1,758.124μs | 1,812.670μs | 43.567μs  | ±2.48% |
| benchStaticRoutes  | ALL Methods,Worst Case     | 3.823mb  | 9,706.135μs | 9,165.860μs | 9,613.744μs | 9,950.180μs | 268.004μs | ±2.79% |
| benchStaticRoutes  | GET Method,Invalid Method  | 3.821mb  | 365.586μs   | 360.040μs   | 369.942μs   | 388.100μs   | 9.674μs   | ±2.61% |
| benchStaticRoutes  | ALL Methods,Invalid Method | 3.823mb  | 351.925μs   | 328.770μs   | 344.936μs   | 353.420μs   | 10.263μs  | ±2.98% |
| benchDynamicRoutes | GET Method,Best Case       | 3.821mb  | 4.439μs     | 4.370μs     | 4.510μs     | 4.730μs     | 0.130μs   | ±2.88% |
| benchDynamicRoutes | ALL Methods,Best Case      | 3.823mb  | 25.765μs    | 25.470μs    | 26.050μs    | 27.220μs    | 0.617μs   | ±2.37% |
| benchDynamicRoutes | GET Method,Average Case    | 3.821mb  | 916.927μs   | 906.630μs   | 929.296μs   | 953.610μs   | 18.711μs  | ±2.01% |
| benchDynamicRoutes | ALL Methods,Average Case   | 3.823mb  | 4,609.892μs | 4,410.820μs | 4,619.008μs | 4,838.240μs | 137.662μs | ±2.98% |
| benchDynamicRoutes | GET Method,Worst Case      | 3.821mb  | 1,716.790μs | 1,646.540μs | 1,694.282μs | 1,738.350μs | 36.871μs  | ±2.18% |
| benchDynamicRoutes | ALL Methods,Worst Case     | 3.823mb  | 9,050.575μs | 8,659.000μs | 9,001.608μs | 9,270.910μs | 200.057μs | ±2.22% |
| benchDynamicRoutes | GET Method,Invalid Method  | 3.821mb  | 338.236μs   | 334.790μs   | 343.004μs   | 351.560μs   | 7.228μs   | ±2.11% |
| benchDynamicRoutes | ALL Methods,Invalid Method | 3.823mb  | 331.850μs   | 313.110μs   | 326.018μs   | 339.490μs   | 10.539μs  | ±3.23% |
| benchOtherRoutes   | GET Method,Non Existent    | 3.821mb  | 680.405μs   | 662.230μs   | 675.868μs   | 692.680μs   | 11.828μs  | ±1.75% |
| benchOtherRoutes   | ALL Methods,Non Existent   | 3.823mb  | 673.177μs   | 643.780μs   | 667.866μs   | 682.900μs   | 13.448μs  | ±2.01% |
+--------------------+----------------------------+----------+-------------+-------------+-------------+-------------+-----------+--------+

SymfonyRouter
+--------------------+--------------------------------+----------+-----------+-----------+-----------+-----------+---------+--------+
| subject            | set                            | mem_peak | mode      | best      | mean      | worst     | stdev   | rstdev |
+--------------------+--------------------------------+----------+-----------+-----------+-----------+-----------+---------+--------+
| benchStaticRoutes  | GET Method,Best Case           | 10.927mb | 0.754μs   | 0.700μs   | 0.736μs   | 0.760μs   | 0.026μs | ±3.50% |
| benchStaticRoutes  | ALL Methods,Best Case          | 10.929mb | 3.693μs   | 3.650μs   | 3.750μs   | 3.850μs   | 0.084μs | ±2.23% |
| benchStaticRoutes  | GET Method,Host,Best Case      | 10.927mb | 0.911μs   | 0.880μs   | 0.916μs   | 0.960μs   | 0.027μs | ±2.90% |
| benchStaticRoutes  | GET Method,Average Case        | 10.927mb | 0.787μs   | 0.740μs   | 0.772μs   | 0.800μs   | 0.023μs | ±3.00% |
| benchStaticRoutes  | ALL Methods,Average Case       | 10.929mb | 3.682μs   | 3.460μs   | 3.630μs   | 3.720μs   | 0.096μs | ±2.64% |
| benchStaticRoutes  | GET Method,Host,Average Case   | 10.927mb | 0.873μs   | 0.870μs   | 0.882μs   | 0.910μs   | 0.016μs | ±1.81% |
| benchStaticRoutes  | GET Method,Worst Case          | 10.927mb | 0.712μs   | 0.710μs   | 0.734μs   | 0.770μs   | 0.029μs | ±4.00% |
| benchStaticRoutes  | ALL Methods,Worst Case         | 10.929mb | 3.921μs   | 3.770μs   | 3.886μs   | 3.960μs   | 0.070μs | ±1.80% |
| benchStaticRoutes  | GET Method,Host,Worst Case     | 10.927mb | 1.036μs   | 1.020μs   | 1.044μs   | 1.080μs   | 0.021μs | ±1.97% |
| benchStaticRoutes  | GET Method,Invalid Method      | 10.928mb | 32.052μs  | 30.750μs  | 32.264μs  | 33.570μs  | 1.025μs | ±3.18% |
| benchStaticRoutes  | ALL Methods,Invalid Method     | 10.930mb | 35.218μs  | 34.320μs  | 35.354μs  | 36.280μs  | 0.711μs | ±2.01% |
| benchStaticRoutes  | GET Method,Host,Invalid Method | 10.928mb | 42.178μs  | 40.470μs  | 41.862μs  | 42.950μs  | 0.860μs | ±2.05% |
| benchDynamicRoutes | GET Method,Best Case           | 10.927mb | 1.457μs   | 1.380μs   | 1.450μs   | 1.510μs   | 0.042μs | ±2.89% |
| benchDynamicRoutes | ALL Methods,Best Case          | 10.929mb | 6.959μs   | 6.890μs   | 6.946μs   | 7.010μs   | 0.046μs | ±0.66% |
| benchDynamicRoutes | GET Method,Host,Best Case      | 10.928mb | 1.723μs   | 1.600μs   | 1.684μs   | 1.750μs   | 0.059μs | ±3.47% |
| benchDynamicRoutes | GET Method,Average Case        | 10.927mb | 10.149μs  | 9.950μs   | 10.296μs  | 10.750μs  | 0.307μs | ±2.98% |
| benchDynamicRoutes | ALL Methods,Average Case       | 10.929mb | 43.967μs  | 41.970μs  | 43.774μs  | 45.230μs  | 1.145μs | ±2.62% |
| benchDynamicRoutes | GET Method,Host,Average Case   | 10.928mb | 13.610μs  | 13.300μs  | 13.956μs  | 14.630μs  | 0.556μs | ±3.98% |
| benchDynamicRoutes | GET Method,Worst Case          | 10.927mb | 38.278μs  | 37.710μs  | 38.842μs  | 40.500μs  | 1.033μs | ±2.66% |
| benchDynamicRoutes | ALL Methods,Worst Case         | 10.929mb | 174.277μs | 166.900μs | 172.126μs | 175.240μs | 3.270μs | ±1.90% |
| benchDynamicRoutes | GET Method,Host,Worst Case     | 10.928mb | 48.584μs  | 46.720μs  | 48.462μs  | 50.050μs  | 1.074μs | ±2.22% |
| benchDynamicRoutes | GET Method,Invalid Method      | 10.928mb | 101.335μs | 98.380μs  | 101.494μs | 104.860μs | 2.129μs | ±2.10% |
| benchDynamicRoutes | ALL Methods,Invalid Method     | 10.930mb | 100.500μs | 97.140μs  | 99.970μs  | 102.770μs | 2.028μs | ±2.03% |
| benchDynamicRoutes | GET Method,Host,Invalid Method | 10.928mb | 113.796μs | 111.480μs | 113.526μs | 115.360μs | 1.346μs | ±1.19% |
| benchOtherRoutes   | GET Method,Non Existent        | 10.928mb | 23.476μs  | 23.050μs  | 23.664μs  | 24.330μs  | 0.459μs | ±1.94% |
| benchOtherRoutes   | ALL Methods,Non Existent       | 10.930mb | 24.521μs  | 24.060μs  | 24.850μs  | 25.990μs  | 0.676μs | ±2.72% |
| benchOtherRoutes   | GET Method,Host,Non Existent   | 10.928mb | 31.181μs  | 30.650μs  | 31.570μs  | 32.560μs  | 0.707μs | ±2.24% |
+--------------------+--------------------------------+----------+-----------+-----------+-----------+-----------+---------+--------+

NetteRouter
+--------------------+--------------------------------+----------+--------------+--------------+--------------+--------------+-----------+--------+
| subject            | set                            | mem_peak | mode         | best         | mean         | worst        | stdev     | rstdev |
+--------------------+--------------------------------+----------+--------------+--------------+--------------+--------------+-----------+--------+
| benchStaticRoutes  | GET Method,Best Case           | 3.821mb  | 3.627μs      | 3.390μs      | 3.544μs      | 3.700μs      | 0.129μs   | ±3.63% |
| benchStaticRoutes  | ALL Methods,Best Case          | 3.823mb  | 11.936μs     | 11.680μs     | 12.048μs     | 12.600μs     | 0.329μs   | ±2.73% |
| benchStaticRoutes  | GET Method,Host,Best Case      | 3.821mb  | 9.285μs      | 8.900μs      | 9.254μs      | 9.560μs      | 0.210μs   | ±2.27% |
| benchStaticRoutes  | GET Method,Average Case        | 3.821mb  | 1,422.536μs  | 1,411.090μs  | 1,433.966μs  | 1,481.560μs  | 24.873μs  | ±1.73% |
| benchStaticRoutes  | ALL Methods,Average Case       | 3.823mb  | 7,129.844μs  | 6,928.670μs  | 7,161.348μs  | 7,442.670μs  | 172.903μs | ±2.41% |
| benchStaticRoutes  | GET Method,Host,Average Case   | 3.821mb  | 1,578.265μs  | 1,528.020μs  | 1,567.490μs  | 1,606.060μs  | 27.925μs  | ±1.78% |
| benchStaticRoutes  | GET Method,Worst Case          | 3.821mb  | 2,853.845μs  | 2,663.060μs  | 2,787.248μs  | 2,906.180μs  | 99.248μs  | ±3.56% |
| benchStaticRoutes  | ALL Methods,Worst Case         | 3.823mb  | 14,465.322μs | 13,861.570μs | 14,330.318μs | 14,711.480μs | 297.480μs | ±2.08% |
| benchStaticRoutes  | GET Method,Host,Worst Case     | 3.821mb  | 3,206.385μs  | 3,058.870μs  | 3,193.802μs  | 3,316.140μs  | 84.112μs  | ±2.63% |
| benchStaticRoutes  | GET Method,Invalid Method      | 3.821mb  | 2,924.171μs  | 2,827.470μs  | 2,927.256μs  | 3,030.820μs  | 65.537μs  | ±2.24% |
| benchStaticRoutes  | ALL Methods,Invalid Method     | 3.823mb  | 2,732.412μs  | 2,670.370μs  | 2,726.330μs  | 2,772.970μs  | 34.253μs  | ±1.26% |
| benchStaticRoutes  | GET Method,Host,Invalid Method | 3.821mb  | 3,283.922μs  | 3,056.440μs  | 3,205.506μs  | 3,311.360μs  | 108.028μs | ±3.37% |
| benchDynamicRoutes | GET Method,Best Case           | 3.821mb  | 2,900.881μs  | 2,807.160μs  | 2,876.032μs  | 2,926.590μs  | 44.232μs  | ±1.54% |
| benchDynamicRoutes | ALL Methods,Best Case          | 3.823mb  | 14,667.582μs | 14,112.860μs | 14,505.492μs | 14,745.590μs | 249.966μs | ±1.72% |
| benchDynamicRoutes | GET Method,Host,Best Case      | 3.821mb  | 13.013μs     | 12.750μs     | 13.074μs     | 13.330μs     | 0.224μs   | ±1.71% |
| benchDynamicRoutes | GET Method,Average Case        | 3.821mb  | 2,776.206μs  | 2,733.860μs  | 2,817.442μs  | 2,934.220μs  | 73.152μs  | ±2.60% |
| benchDynamicRoutes | ALL Methods,Average Case       | 3.823mb  | 14,324.938μs | 13,812.690μs | 14,294.906μs | 14,697.010μs | 312.430μs | ±2.19% |
| benchDynamicRoutes | GET Method,Host,Average Case   | 3.821mb  | 1,690.389μs  | 1,646.280μs  | 1,702.876μs  | 1,778.090μs  | 50.613μs  | ±2.97% |
| benchDynamicRoutes | GET Method,Worst Case          | 3.821mb  | 2,775.092μs  | 2,731.520μs  | 2,810.660μs  | 2,945.610μs  | 74.685μs  | ±2.66% |
| benchDynamicRoutes | ALL Methods,Worst Case         | 3.823mb  | 15,329.444μs | 14,192.750μs | 14,916.140μs | 15,390.380μs | 555.937μs | ±3.73% |
| benchDynamicRoutes | GET Method,Host,Worst Case     | 3.821mb  | 3,163.592μs  | 3,117.560μs  | 3,258.352μs  | 3,419.130μs  | 134.609μs | ±4.13% |
| benchDynamicRoutes | GET Method,Invalid Method      | 3.821mb  | 2,957.606μs  | 2,832.390μs  | 2,932.662μs  | 2,994.790μs  | 56.072μs  | ±1.91% |
| benchDynamicRoutes | ALL Methods,Invalid Method     | 3.823mb  | 2,952.960μs  | 2,881.570μs  | 2,949.850μs  | 3,017.690μs  | 45.156μs  | ±1.53% |
| benchDynamicRoutes | GET Method,Host,Invalid Method | 3.822mb  | 3,414.900μs  | 3,287.270μs  | 3,368.276μs  | 3,438.590μs  | 65.406μs  | ±1.94% |
| benchOtherRoutes   | GET Method,Non Existent        | 3.821mb  | 2,732.750μs  | 2,670.240μs  | 2,770.818μs  | 2,884.620μs  | 77.167μs  | ±2.78% |
| benchOtherRoutes   | ALL Methods,Non Existent       | 3.823mb  | 3,018.665μs  | 2,997.150μs  | 3,083.978μs  | 3,208.580μs  | 90.691μs  | ±2.94% |
| benchOtherRoutes   | GET Method,Host,Non Existent   | 3.822mb  | 3,498.465μs  | 3,351.290μs  | 3,448.058μs  | 3,531.210μs  | 73.813μs  | ±2.14% |
+--------------------+--------------------------------+----------+--------------+--------------+--------------+--------------+-----------+--------+

LaravelRouter
+--------------------+--------------------------------+----------+-------------+-------------+-------------+-------------+-----------+--------+
| subject            | set                            | mem_peak | mode        | best        | mean        | worst       | stdev     | rstdev |
+--------------------+--------------------------------+----------+-------------+-------------+-------------+-------------+-----------+--------+
| benchStaticRoutes  | GET Method,Best Case           | 4.802mb  | 370.056μs   | 366.210μs   | 373.564μs   | 387.570μs   | 7.476μs   | ±2.00% |
| benchStaticRoutes  | ALL Methods,Best Case          | 4.805mb  | 1,951.235μs | 1,865.890μs | 1,956.104μs | 2,053.870μs | 60.089μs  | ±3.07% |
| benchStaticRoutes  | GET Method,Host,Best Case      | 4.808mb  | 374.402μs   | 370.290μs   | 378.704μs   | 387.100μs   | 6.713μs   | ±1.77% |
| benchStaticRoutes  | GET Method,Average Case        | 6.496mb  | 929.457μs   | 894.660μs   | 923.328μs   | 943.780μs   | 16.810μs  | ±1.82% |
| benchStaticRoutes  | ALL Methods,Average Case       | 6.498mb  | 4,720.229μs | 4,641.470μs | 4,705.710μs | 4,744.400μs | 34.792μs  | ±0.74% |
| benchStaticRoutes  | GET Method,Host,Average Case   | 6.501mb  | 994.378μs   | 964.700μs   | 1,002.212μs | 1,045.800μs | 28.580μs  | ±2.85% |
| benchStaticRoutes  | GET Method,Worst Case          | 8.231mb  | 1,508.129μs | 1,472.480μs | 1,502.304μs | 1,530.310μs | 20.005μs  | ±1.33% |
| benchStaticRoutes  | ALL Methods,Worst Case         | 8.233mb  | 7,411.741μs | 6,930.010μs | 7,241.174μs | 7,458.260μs | 233.675μs | ±3.23% |
| benchStaticRoutes  | GET Method,Host,Worst Case     | 8.235mb  | 1,439.478μs | 1,420.090μs | 1,449.722μs | 1,494.580μs | 26.090μs  | ±1.80% |
| benchStaticRoutes  | GET Method,Invalid Method      | 7.982mb  | 8,050.928μs | 7,981.780μs | 8,171.246μs | 8,383.310μs | 169.711μs | ±2.08% |
| benchStaticRoutes  | ALL Methods,Invalid Method     | 7.984mb  | 7,929.691μs | 7,862.880μs | 8,013.728μs | 8,357.670μs | 180.310μs | ±2.25% |
| benchStaticRoutes  | GET Method,Host,Invalid Method | 7.986mb  | 8,053.369μs | 7,980.780μs | 8,165.780μs | 8,475.350μs | 188.474μs | ±2.31% |
| benchDynamicRoutes | GET Method,Best Case           | 4.806mb  | 359.106μs   | 349.390μs   | 355.816μs   | 362.120μs   | 5.075μs   | ±1.43% |
| benchDynamicRoutes | ALL Methods,Best Case          | 4.808mb  | 1,879.958μs | 1,871.220μs | 1,888.060μs | 1,921.390μs | 18.325μs  | ±0.97% |
| benchDynamicRoutes | GET Method,Host,Best Case      | 4.813mb  | 397.491μs   | 387.230μs   | 394.152μs   | 400.920μs   | 5.371μs   | ±1.36% |
| benchDynamicRoutes | GET Method,Average Case        | 6.499mb  | 965.789μs   | 947.590μs   | 991.580μs   | 1,037.010μs | 37.870μs  | ±3.82% |
| benchDynamicRoutes | ALL Methods,Average Case       | 6.501mb  | 4,884.076μs | 4,553.490μs | 4,775.902μs | 4,970.600μs | 167.465μs | ±3.51% |
| benchDynamicRoutes | GET Method,Host,Average Case   | 6.505mb  | 917.160μs   | 886.790μs   | 922.382μs   | 965.970μs   | 25.341μs  | ±2.75% |
| benchDynamicRoutes | GET Method,Worst Case          | 8.234mb  | 1,403.957μs | 1,372.030μs | 1,414.600μs | 1,449.350μs | 29.503μs  | ±2.09% |
| benchDynamicRoutes | ALL Methods,Worst Case         | 8.236mb  | 7,091.919μs | 6,989.190μs | 7,166.728μs | 7,372.360μs | 140.419μs | ±1.96% |
| benchDynamicRoutes | GET Method,Host,Worst Case     | 8.239mb  | 1,523.220μs | 1,458.720μs | 1,504.490μs | 1,552.890μs | 36.553μs  | ±2.43% |
| benchDynamicRoutes | GET Method,Invalid Method      | 7.984mb  | 8,396.410μs | 8,035.270μs | 8,266.256μs | 8,456.600μs | 180.815μs | ±2.19% |
| benchDynamicRoutes | ALL Methods,Invalid Method     | 7.986mb  | 8,154.782μs | 7,815.470μs | 8,055.116μs | 8,253.320μs | 167.206μs | ±2.08% |
| benchDynamicRoutes | GET Method,Host,Invalid Method | 7.990mb  | 8,129.809μs | 7,956.750μs | 8,197.858μs | 8,550.260μs | 195.707μs | ±2.39% |
| benchOtherRoutes   | GET Method,Non Existent        | 7.989mb  | 7,722.918μs | 7,615.330μs | 7,801.348μs | 8,143.960μs | 180.045μs | ±2.31% |
| benchOtherRoutes   | ALL Methods,Non Existent       | 7.991mb  | 7,987.794μs | 7,946.960μs | 8,015.622μs | 8,113.890μs | 58.234μs  | ±0.73% |
| benchOtherRoutes   | GET Method,Host,Non Existent   | 7.989mb  | 8,395.811μs | 8,279.560μs | 8,538.840μs | 8,796.120μs | 214.642μs | ±2.51% |
+--------------------+--------------------------------+----------+-------------+-------------+-------------+-------------+-----------+--------+

SpiralRouter
+--------------------+----------------------------+----------+-------------+-------------+-------------+-------------+----------+--------+
| subject            | set                        | mem_peak | mode        | best        | mean        | worst       | stdev    | rstdev |
+--------------------+----------------------------+----------+-------------+-------------+-------------+-------------+----------+--------+
| benchStaticRoutes  | GET Method,Best Case       | 3.821mb  | 25.471μs    | 24.110μs    | 25.172μs    | 25.810μs    | 0.620μs  | ±2.46% |
| benchStaticRoutes  | ALL Methods,Best Case      | 3.823mb  | 127.461μs   | 119.400μs   | 124.808μs   | 130.810μs   | 4.681μs  | ±3.75% |
| benchStaticRoutes  | GET Method,Average Case    | 3.821mb  | 329.920μs   | 324.370μs   | 333.680μs   | 349.470μs   | 8.586μs  | ±2.57% |
| benchStaticRoutes  | ALL Methods,Average Case   | 3.823mb  | 1,727.256μs | 1,703.730μs | 1,758.482μs | 1,825.160μs | 48.087μs | ±2.73% |
| benchStaticRoutes  | GET Method,Worst Case      | 3.821mb  | 681.682μs   | 651.790μs   | 675.922μs   | 694.220μs   | 14.828μs | ±2.19% |
| benchStaticRoutes  | ALL Methods,Worst Case     | 3.823mb  | 3,385.078μs | 3,244.860μs | 3,354.114μs | 3,410.720μs | 61.291μs | ±1.83% |
| benchStaticRoutes  | GET Method,Invalid Method  | 3.821mb  | 162.513μs   | 159.480μs   | 165.284μs   | 170.630μs   | 4.534μs  | ±2.74% |
| benchStaticRoutes  | ALL Methods,Invalid Method | 3.823mb  | 167.670μs   | 158.450μs   | 165.188μs   | 171.950μs   | 5.057μs  | ±3.06% |
| benchDynamicRoutes | GET Method,Best Case       | 3.821mb  | 28.709μs    | 27.700μs    | 28.356μs    | 28.910μs    | 0.511μs  | ±1.80% |
| benchDynamicRoutes | ALL Methods,Best Case      | 3.823mb  | 132.150μs   | 130.210μs   | 133.254μs   | 138.140μs   | 2.688μs  | ±2.02% |
| benchDynamicRoutes | GET Method,Average Case    | 3.821mb  | 378.062μs   | 361.430μs   | 374.370μs   | 381.570μs   | 7.398μs  | ±1.98% |
| benchDynamicRoutes | ALL Methods,Average Case   | 3.823mb  | 1,836.421μs | 1,807.780μs | 1,852.808μs | 1,926.770μs | 39.961μs | ±2.16% |
| benchDynamicRoutes | GET Method,Worst Case      | 3.821mb  | 676.017μs   | 660.400μs   | 674.920μs   | 688.110μs   | 9.488μs  | ±1.41% |
| benchDynamicRoutes | ALL Methods,Worst Case     | 3.823mb  | 3,413.022μs | 3,323.450μs | 3,405.426μs | 3,475.010μs | 51.352μs | ±1.51% |
| benchDynamicRoutes | GET Method,Invalid Method  | 3.821mb  | 173.010μs   | 167.690μs   | 173.098μs   | 178.740μs   | 3.622μs  | ±2.09% |
| benchDynamicRoutes | ALL Methods,Invalid Method | 3.823mb  | 170.745μs   | 168.300μs   | 172.420μs   | 178.800μs   | 3.668μs  | ±2.13% |
| benchOtherRoutes   | GET Method,Non Existent    | 3.821mb  | 610.431μs   | 604.170μs   | 614.858μs   | 631.990μs   | 10.208μs | ±1.66% |
| benchOtherRoutes   | ALL Methods,Non Existent   | 3.823mb  | 569.109μs   | 565.820μs   | 578.954μs   | 598.770μs   | 13.878μs | ±2.40% |
+--------------------+----------------------------+----------+-------------+-------------+-------------+-------------+----------+--------+

FlightRouting
+--------------------+--------------------------------+----------+-----------+-----------+-----------+-----------+---------+--------+
| subject            | set                            | mem_peak | mode      | best      | mean      | worst     | stdev   | rstdev |
+--------------------+--------------------------------+----------+-----------+-----------+-----------+-----------+---------+--------+
| benchStaticRoutes  | GET Method,Best Case           | 3.821mb  | 1.525μs   | 1.460μs   | 1.508μs   | 1.540μs   | 0.029μs | ±1.94% |
| benchStaticRoutes  | ALL Methods,Best Case          | 3.823mb  | 3.701μs   | 3.550μs   | 3.684μs   | 3.800μs   | 0.083μs | ±2.24% |
| benchStaticRoutes  | GET Method,Host,Best Case      | 3.821mb  | 2.509μs   | 2.360μs   | 2.472μs   | 2.550μs   | 0.069μs | ±2.77% |
| benchStaticRoutes  | GET Method,Average Case        | 3.821mb  | 1.723μs   | 1.700μs   | 1.766μs   | 1.850μs   | 0.062μs | ±3.52% |
| benchStaticRoutes  | ALL Methods,Average Case       | 3.823mb  | 3.597μs   | 3.390μs   | 3.556μs   | 3.620μs   | 0.085μs | ±2.40% |
| benchStaticRoutes  | GET Method,Host,Average Case   | 3.821mb  | 2.501μs   | 2.380μs   | 2.480μs   | 2.560μs   | 0.062μs | ±2.49% |
| benchStaticRoutes  | GET Method,Worst Case          | 3.821mb  | 1.571μs   | 1.540μs   | 1.582μs   | 1.640μs   | 0.033μs | ±2.09% |
| benchStaticRoutes  | ALL Methods,Worst Case         | 3.823mb  | 3.320μs   | 3.240μs   | 3.306μs   | 3.350μs   | 0.037μs | ±1.13% |
| benchStaticRoutes  | GET Method,Host,Worst Case     | 3.821mb  | 2.126μs   | 2.010μs   | 2.106μs   | 2.170μs   | 0.053μs | ±2.52% |
| benchStaticRoutes  | GET Method,Invalid Method      | 3.821mb  | 2.905μs   | 2.770μs   | 2.888μs   | 2.980μs   | 0.070μs | ±2.41% |
| benchStaticRoutes  | ALL Methods,Invalid Method     | 3.823mb  | 2.884μs   | 2.860μs   | 2.908μs   | 2.970μs   | 0.042μs | ±1.43% |
| benchStaticRoutes  | GET Method,Host,Invalid Method | 3.822mb  | 3.095μs   | 3.010μs   | 3.166μs   | 3.310μs   | 0.121μs | ±3.81% |
| benchDynamicRoutes | GET Method,Best Case           | 3.821mb  | 3.154μs   | 2.930μs   | 3.082μs   | 3.170μs   | 0.102μs | ±3.30% |
| benchDynamicRoutes | ALL Methods,Best Case          | 3.823mb  | 7.583μs   | 7.410μs   | 7.794μs   | 8.180μs   | 0.325μs | ±4.17% |
| benchDynamicRoutes | GET Method,Host,Best Case      | 3.821mb  | 3.121μs   | 2.990μs   | 3.146μs   | 3.290μs   | 0.107μs | ±3.42% |
| benchDynamicRoutes | GET Method,Average Case        | 3.821mb  | 20.208μs  | 19.510μs  | 20.002μs  | 20.290μs  | 0.313μs | ±1.56% |
| benchDynamicRoutes | ALL Methods,Average Case       | 3.823mb  | 53.069μs  | 52.710μs  | 53.288μs  | 54.290μs  | 0.530μs | ±0.99% |
| benchDynamicRoutes | GET Method,Host,Average Case   | 3.821mb  | 35.165μs  | 34.770μs  | 36.010μs  | 37.740μs  | 1.203μs | ±3.34% |
| benchDynamicRoutes | GET Method,Worst Case          | 3.821mb  | 55.834μs  | 54.370μs  | 56.354μs  | 57.950μs  | 1.394μs | ±2.47% |
| benchDynamicRoutes | ALL Methods,Worst Case         | 3.823mb  | 265.696μs | 251.890μs | 261.062μs | 269.310μs | 7.225μs | ±2.77% |
| benchDynamicRoutes | GET Method,Host,Worst Case     | 3.821mb  | 79.049μs  | 73.440μs  | 77.248μs  | 80.660μs  | 2.817μs | ±3.65% |
| benchDynamicRoutes | GET Method,Invalid Method      | 3.821mb  | 60.263μs  | 58.360μs  | 59.702μs  | 61.130μs  | 1.074μs | ±1.80% |
| benchDynamicRoutes | ALL Methods,Invalid Method     | 3.823mb  | 59.487μs  | 56.020μs  | 58.804μs  | 60.150μs  | 1.490μs | ±2.53% |
| benchDynamicRoutes | GET Method,Host,Invalid Method | 3.822mb  | 77.303μs  | 77.130μs  | 78.590μs  | 80.820μs  | 1.721μs | ±2.19% |
| benchOtherRoutes   | GET Method,Non Existent        | 3.821mb  | 5.143μs   | 5.060μs   | 5.150μs   | 5.240μs   | 0.061μs | ±1.19% |
| benchOtherRoutes   | ALL Methods,Non Existent       | 3.823mb  | 4.667μs   | 4.580μs   | 4.688μs   | 4.830μs   | 0.080μs | ±1.71% |
| benchOtherRoutes   | GET Method,Host,Non Existent   | 3.822mb  | 57.971μs  | 57.020μs  | 58.604μs  | 61.360μs  | 1.508μs | ±2.57% |
+--------------------+--------------------------------+----------+-----------+-----------+-----------+-----------+---------+--------+

SunriseRouter
+--------------------+--------------------------------+----------+-------------+-------------+-------------+-------------+-----------+--------+
| subject            | set                            | mem_peak | mode        | best        | mean        | worst       | stdev     | rstdev |
+--------------------+--------------------------------+----------+-------------+-------------+-------------+-------------+-----------+--------+
| benchStaticRoutes  | GET Method,Best Case           | 3.823mb  | 19.662μs    | 19.190μs    | 19.532μs    | 19.810μs    | 0.231μs   | ±1.18% |
| benchStaticRoutes  | ALL Methods,Best Case          | 3.825mb  | 70.214μs    | 65.900μs    | 68.886μs    | 71.890μs    | 2.319μs   | ±3.37% |
| benchStaticRoutes  | GET Method,Host,Best Case      | 3.823mb  | 29.096μs    | 27.090μs    | 28.462μs    | 29.430μs    | 0.925μs   | ±3.25% |
| benchStaticRoutes  | GET Method,Average Case        | 3.823mb  | 452.769μs   | 447.040μs   | 462.832μs   | 481.080μs   | 14.185μs  | ±3.06% |
| benchStaticRoutes  | ALL Methods,Average Case       | 3.825mb  | 2,280.778μs | 2,256.910μs | 2,317.024μs | 2,421.730μs | 62.277μs  | ±2.69% |
| benchStaticRoutes  | GET Method,Host,Average Case   | 3.823mb  | 849.950μs   | 819.040μs   | 851.082μs   | 883.510μs   | 21.167μs  | ±2.49% |
| benchStaticRoutes  | GET Method,Worst Case          | 3.823mb  | 922.166μs   | 892.930μs   | 916.722μs   | 934.200μs   | 14.142μs  | ±1.54% |
| benchStaticRoutes  | ALL Methods,Worst Case         | 3.825mb  | 4,742.166μs | 4,712.300μs | 4,777.300μs | 4,860.470μs | 58.159μs  | ±1.22% |
| benchStaticRoutes  | GET Method,Host,Worst Case     | 3.823mb  | 1,814.726μs | 1,790.800μs | 1,819.406μs | 1,855.490μs | 20.735μs  | ±1.14% |
| benchStaticRoutes  | GET Method,Invalid Method      | 3.821mb  | 920.918μs   | 861.630μs   | 906.374μs   | 931.760μs   | 25.916μs  | ±2.86% |
| benchStaticRoutes  | ALL Methods,Invalid Method     | 3.823mb  | 922.356μs   | 867.760μs   | 904.810μs   | 932.640μs   | 25.763μs  | ±2.85% |
| benchStaticRoutes  | GET Method,Host,Invalid Method | 3.821mb  | 1,930.449μs | 1,863.000μs | 1,909.464μs | 1,946.860μs | 32.245μs  | ±1.69% |
| benchDynamicRoutes | GET Method,Best Case           | 3.823mb  | 27.409μs    | 26.800μs    | 27.778μs    | 28.890μs    | 0.796μs   | ±2.87% |
| benchDynamicRoutes | ALL Methods,Best Case          | 3.825mb  | 92.879μs    | 91.740μs    | 93.652μs    | 96.630μs    | 1.799μs   | ±1.92% |
| benchDynamicRoutes | GET Method,Host,Best Case      | 3.823mb  | 33.976μs    | 32.830μs    | 33.896μs    | 34.850μs    | 0.647μs   | ±1.91% |
| benchDynamicRoutes | GET Method,Average Case        | 3.823mb  | 488.937μs   | 485.070μs   | 492.534μs   | 505.030μs   | 7.257μs   | ±1.47% |
| benchDynamicRoutes | ALL Methods,Average Case       | 3.825mb  | 2,494.651μs | 2,441.520μs | 2,506.358μs | 2,589.880μs | 51.826μs  | ±2.07% |
| benchDynamicRoutes | GET Method,Host,Average Case   | 3.823mb  | 845.624μs   | 838.710μs   | 855.732μs   | 873.350μs   | 14.680μs  | ±1.72% |
| benchDynamicRoutes | GET Method,Worst Case          | 3.823mb  | 989.853μs   | 923.600μs   | 967.166μs   | 1,004.400μs | 32.776μs  | ±3.39% |
| benchDynamicRoutes | ALL Methods,Worst Case         | 3.825mb  | 4,601.303μs | 4,534.540μs | 4,684.278μs | 4,875.700μs | 131.146μs | ±2.80% |
| benchDynamicRoutes | GET Method,Host,Worst Case     | 3.823mb  | 1,724.124μs | 1,718.830μs | 1,752.134μs | 1,806.100μs | 38.449μs  | ±2.19% |
| benchDynamicRoutes | GET Method,Invalid Method      | 3.821mb  | 926.968μs   | 872.220μs   | 907.970μs   | 941.640μs   | 28.559μs  | ±3.15% |
| benchDynamicRoutes | ALL Methods,Invalid Method     | 3.823mb  | 977.172μs   | 924.010μs   | 964.788μs   | 1,002.980μs | 29.174μs  | ±3.02% |
| benchDynamicRoutes | GET Method,Host,Invalid Method | 3.821mb  | 1,663.346μs | 1,626.430μs | 1,664.532μs | 1,704.380μs | 24.825μs  | ±1.49% |
| benchOtherRoutes   | GET Method,Non Existent        | 3.821mb  | 932.819μs   | 886.290μs   | 925.038μs   | 951.790μs   | 22.631μs  | ±2.45% |
| benchOtherRoutes   | ALL Methods,Non Existent       | 3.823mb  | 932.963μs   | 901.790μs   | 929.418μs   | 952.040μs   | 16.270μs  | ±1.75% |
| benchOtherRoutes   | GET Method,Host,Non Existent   | 3.821mb  | 1,656.362μs | 1,642.220μs | 1,678.080μs | 1,734.870μs | 36.191μs  | ±2.16% |
+--------------------+--------------------------------+----------+-------------+-------------+-------------+-------------+-----------+--------+

FastRoute
+--------------------+----------------------------+----------+----------+----------+----------+----------+---------+--------+
| subject            | set                        | mem_peak | mode     | best     | mean     | worst    | stdev   | rstdev |
+--------------------+----------------------------+----------+----------+----------+----------+----------+---------+--------+
| benchStaticRoutes  | GET Method,Best Case       | 3.821mb  | 0.286μs  | 0.270μs  | 0.280μs  | 0.290μs  | 0.009μs | ±3.19% |
| benchStaticRoutes  | ALL Methods,Best Case      | 3.823mb  | 1.006μs  | 0.990μs  | 1.020μs  | 1.070μs  | 0.028μs | ±2.77% |
| benchStaticRoutes  | GET Method,Average Case    | 3.821mb  | 0.276μs  | 0.270μs  | 0.278μs  | 0.290μs  | 0.007μs | ±2.69% |
| benchStaticRoutes  | ALL Methods,Average Case   | 3.823mb  | 1.047μs  | 1.030μs  | 1.060μs  | 1.100μs  | 0.028μs | ±2.60% |
| benchStaticRoutes  | GET Method,Worst Case      | 3.821mb  | 0.260μs  | 0.250μs  | 0.260μs  | 0.270μs  | 0.009μs | ±3.44% |
| benchStaticRoutes  | ALL Methods,Worst Case     | 3.823mb  | 1.012μs  | 0.990μs  | 1.034μs  | 1.080μs  | 0.035μs | ±3.38% |
| benchStaticRoutes  | GET Method,Invalid Method  | 3.821mb  | 13.562μs | 13.360μs | 13.714μs | 14.300μs | 0.328μs | ±2.39% |
| benchStaticRoutes  | ALL Methods,Invalid Method | 3.823mb  | 12.969μs | 12.750μs | 13.128μs | 13.740μs | 0.345μs | ±2.63% |
| benchDynamicRoutes | GET Method,Best Case       | 3.821mb  | 0.820μs  | 0.770μs  | 0.808μs  | 0.840μs  | 0.025μs | ±3.07% |
| benchDynamicRoutes | ALL Methods,Best Case      | 3.823mb  | 4.033μs  | 3.990μs  | 4.062μs  | 4.180μs  | 0.067μs | ±1.64% |
| benchDynamicRoutes | GET Method,Average Case    | 3.821mb  | 2.672μs  | 2.580μs  | 2.688μs  | 2.790μs  | 0.074μs | ±2.77% |
| benchDynamicRoutes | ALL Methods,Average Case   | 3.823mb  | 18.676μs | 18.440μs | 18.748μs | 19.140μs | 0.236μs | ±1.26% |
| benchDynamicRoutes | GET Method,Worst Case      | 3.821mb  | 5.892μs  | 5.800μs  | 5.948μs  | 6.190μs  | 0.133μs | ±2.23% |
| benchDynamicRoutes | ALL Methods,Worst Case     | 3.823mb  | 40.126μs | 37.550μs | 39.208μs | 40.590μs | 1.281μs | ±3.27% |
| benchDynamicRoutes | GET Method,Invalid Method  | 3.821mb  | 40.241μs | 39.010μs | 39.784μs | 40.450μs | 0.630μs | ±1.58% |
| benchDynamicRoutes | ALL Methods,Invalid Method | 3.823mb  | 42.587μs | 41.260μs | 42.940μs | 45.070μs | 1.262μs | ±2.94% |
| benchOtherRoutes   | GET Method,Non Existent    | 3.821mb  | 12.803μs | 12.700μs | 13.032μs | 13.430μs | 0.317μs | ±2.43% |
| benchOtherRoutes   | ALL Methods,Non Existent   | 3.823mb  | 12.709μs | 12.450μs | 12.748μs | 13.150μs | 0.263μs | ±2.07% |
+--------------------+----------------------------+----------+----------+----------+----------+----------+---------+--------+

AuraRouter
+--------------------+--------------------------------+----------+--------------+--------------+--------------+--------------+-----------+--------+
| subject            | set                            | mem_peak | mode         | best         | mean         | worst        | stdev     | rstdev |
+--------------------+--------------------------------+----------+--------------+--------------+--------------+--------------+-----------+--------+
| benchStaticRoutes  | GET Method,Best Case           | 3.821mb  | 54.793μs     | 52.230μs     | 54.214μs     | 55.290μs     | 1.107μs   | ±2.04% |
| benchStaticRoutes  | ALL Methods,Best Case          | 3.823mb  | 243.707μs    | 230.300μs    | 239.932μs    | 247.600μs    | 6.371μs   | ±2.66% |
| benchStaticRoutes  | GET Method,Host,Best Case      | 3.821mb  | 64.035μs     | 62.990μs     | 64.740μs     | 67.160μs     | 1.459μs   | ±2.25% |
| benchStaticRoutes  | GET Method,Average Case        | 3.821mb  | 2,745.184μs  | 2,713.730μs  | 2,792.358μs  | 2,882.920μs  | 71.894μs  | ±2.57% |
| benchStaticRoutes  | ALL Methods,Average Case       | 3.823mb  | 14,005.508μs | 13,279.940μs | 13,782.694μs | 14,195.410μs | 368.621μs | ±2.67% |
| benchStaticRoutes  | GET Method,Host,Average Case   | 3.821mb  | 3,607.561μs  | 3,537.090μs  | 3,665.086μs  | 3,809.900μs  | 107.734μs | ±2.94% |
| benchStaticRoutes  | GET Method,Worst Case          | 3.821mb  | 5,214.406μs  | 5,037.340μs  | 5,155.040μs  | 5,244.390μs  | 84.389μs  | ±1.64% |
| benchStaticRoutes  | ALL Methods,Worst Case         | 3.823mb  | 26,402.503μs | 25,967.860μs | 26,559.110μs | 27,161.960μs | 436.204μs | ±1.64% |
| benchStaticRoutes  | GET Method,Host,Worst Case     | 3.821mb  | 7,094.038μs  | 6,719.690μs  | 6,970.058μs  | 7,167.680μs  | 181.538μs | ±2.60% |
| benchStaticRoutes  | GET Method,Invalid Method      | 3.821mb  | 5,139.885μs  | 4,958.070μs  | 5,132.704μs  | 5,298.390μs  | 109.982μs | ±2.14% |
| benchStaticRoutes  | ALL Methods,Invalid Method     | 3.823mb  | 5,126.799μs  | 4,830.830μs  | 5,020.504μs  | 5,172.230μs  | 146.568μs | ±2.92% |
| benchStaticRoutes  | GET Method,Host,Invalid Method | 3.822mb  | 7,120.350μs  | 6,915.370μs  | 7,089.802μs  | 7,222.540μs  | 102.207μs | ±1.44% |
| benchDynamicRoutes | GET Method,Best Case           | 3.821mb  | 55.321μs     | 54.260μs     | 55.704μs     | 57.670μs     | 1.191μs   | ±2.14% |
| benchDynamicRoutes | ALL Methods,Best Case          | 3.823mb  | 256.985μs    | 246.350μs    | 256.464μs    | 265.970μs    | 6.325μs   | ±2.47% |
| benchDynamicRoutes | GET Method,Host,Best Case      | 3.821mb  | 68.874μs     | 67.930μs     | 69.776μs     | 73.010μs     | 1.836μs   | ±2.63% |
| benchDynamicRoutes | GET Method,Average Case        | 3.821mb  | 2,558.723μs  | 2,521.200μs  | 2,590.054μs  | 2,652.530μs  | 54.544μs  | ±2.11% |
| benchDynamicRoutes | ALL Methods,Average Case       | 3.823mb  | 14,308.735μs | 13,567.750μs | 14,163.646μs | 14,564.180μs | 344.039μs | ±2.43% |
| benchDynamicRoutes | GET Method,Host,Average Case   | 3.821mb  | 3,772.651μs  | 3,687.200μs  | 3,782.654μs  | 3,893.170μs  | 65.586μs  | ±1.73% |
| benchDynamicRoutes | GET Method,Worst Case          | 3.821mb  | 5,430.711μs  | 5,316.140μs  | 5,404.748μs  | 5,452.610μs  | 50.416μs  | ±0.93% |
| benchDynamicRoutes | ALL Methods,Worst Case         | 3.823mb  | 26,632.882μs | 25,849.300μs | 26,649.334μs | 27,444.560μs | 523.784μs | ±1.97% |
| benchDynamicRoutes | GET Method,Host,Worst Case     | 3.821mb  | 7,505.935μs  | 7,425.940μs  | 7,478.166μs  | 7,528.390μs  | 41.556μs  | ±0.56% |
| benchDynamicRoutes | GET Method,Invalid Method      | 3.821mb  | 4,949.197μs  | 4,867.980μs  | 5,016.474μs  | 5,182.390μs  | 118.525μs | ±2.36% |
| benchDynamicRoutes | ALL Methods,Invalid Method     | 3.823mb  | 5,078.449μs  | 4,902.730μs  | 5,067.576μs  | 5,216.690μs  | 101.192μs | ±2.00% |
| benchDynamicRoutes | GET Method,Host,Invalid Method | 3.822mb  | 7,297.310μs  | 6,890.180μs  | 7,154.220μs  | 7,375.130μs  | 207.367μs | ±2.90% |
| benchOtherRoutes   | GET Method,Non Existent        | 3.821mb  | 5,339.672μs  | 5,239.260μs  | 5,352.764μs  | 5,454.850μs  | 76.685μs  | ±1.43% |
| benchOtherRoutes   | ALL Methods,Non Existent       | 3.823mb  | 5,291.087μs  | 5,186.570μs  | 5,277.878μs  | 5,352.950μs  | 57.730μs  | ±1.09% |
| benchOtherRoutes   | GET Method,Host,Non Existent   | 3.822mb  | 7,066.513μs  | 6,826.970μs  | 6,998.780μs  | 7,112.300μs  | 107.661μs | ±1.54% |
+--------------------+--------------------------------+----------+--------------+--------------+--------------+--------------+-----------+--------+
```

### Benchmark runned with file cache support.

```
FlightRoutingCached
+--------------------+--------------------------------+----------+----------+----------+----------+----------+---------+--------+
| subject            | set                            | mem_peak | mode     | best     | mean     | worst    | stdev   | rstdev |
+--------------------+--------------------------------+----------+----------+----------+----------+----------+---------+--------+
| benchStaticRoutes  | GET Method,Best Case           | 4.616mb  | 1.821μs  | 1.710μs  | 1.792μs  | 1.870μs  | 0.060μs | ±3.34% |
| benchStaticRoutes  | ALL Methods,Best Case          | 4.618mb  | 3.500μs  | 3.360μs  | 3.474μs  | 3.550μs  | 0.072μs | ±2.06% |
| benchStaticRoutes  | GET Method,Host,Best Case      | 4.616mb  | 2.331μs  | 2.250μs  | 2.310μs  | 2.360μs  | 0.039μs | ±1.71% |
| benchStaticRoutes  | GET Method,Average Case        | 4.616mb  | 1.726μs  | 1.650μs  | 1.702μs  | 1.740μs  | 0.035μs | ±2.08% |
| benchStaticRoutes  | ALL Methods,Average Case       | 4.618mb  | 3.849μs  | 3.680μs  | 3.824μs  | 3.930μs  | 0.082μs | ±2.14% |
| benchStaticRoutes  | GET Method,Host,Average Case   | 4.616mb  | 2.702μs  | 2.660μs  | 2.712μs  | 2.780μs  | 0.041μs | ±1.52% |
| benchStaticRoutes  | GET Method,Worst Case          | 4.616mb  | 1.652μs  | 1.580μs  | 1.640μs  | 1.680μs  | 0.033μs | ±2.04% |
| benchStaticRoutes  | ALL Methods,Worst Case         | 4.618mb  | 4.134μs  | 3.850μs  | 4.050μs  | 4.200μs  | 0.134μs | ±3.30% |
| benchStaticRoutes  | GET Method,Host,Worst Case     | 4.616mb  | 2.429μs  | 2.310μs  | 2.428μs  | 2.540μs  | 0.076μs | ±3.13% |
| benchStaticRoutes  | GET Method,Invalid Method      | 4.616mb  | 2.914μs  | 2.830μs  | 2.926μs  | 3.040μs  | 0.067μs | ±2.29% |
| benchStaticRoutes  | ALL Methods,Invalid Method     | 4.618mb  | 3.056μs  | 3.010μs  | 3.070μs  | 3.150μs  | 0.046μs | ±1.50% |
| benchStaticRoutes  | GET Method,Host,Invalid Method | 4.616mb  | 3.256μs  | 3.240μs  | 3.294μs  | 3.440μs  | 0.076μs | ±2.32% |
| benchDynamicRoutes | GET Method,Best Case           | 4.616mb  | 3.089μs  | 2.930μs  | 3.084μs  | 3.230μs  | 0.098μs | ±3.19% |
| benchDynamicRoutes | ALL Methods,Best Case          | 4.618mb  | 9.007μs  | 8.740μs  | 9.058μs  | 9.430μs  | 0.232μs | ±2.56% |
| benchDynamicRoutes | GET Method,Host,Best Case      | 4.616mb  | 3.205μs  | 3.150μs  | 3.278μs  | 3.440μs  | 0.113μs | ±3.45% |
| benchDynamicRoutes | GET Method,Average Case        | 4.616mb  | 5.659μs  | 5.600μs  | 5.712μs  | 5.890μs  | 0.103μs | ±1.80% |
| benchDynamicRoutes | ALL Methods,Average Case       | 4.618mb  | 17.811μs | 17.280μs | 17.688μs | 17.960μs | 0.244μs | ±1.38% |
| benchDynamicRoutes | GET Method,Host,Average Case   | 4.616mb  | 37.981μs | 37.040μs | 38.160μs | 39.130μs | 0.762μs | ±2.00% |
| benchDynamicRoutes | GET Method,Worst Case          | 4.616mb  | 5.156μs  | 4.810μs  | 5.030μs  | 5.200μs  | 0.175μs | ±3.47% |
| benchDynamicRoutes | ALL Methods,Worst Case         | 4.618mb  | 15.378μs | 15.310μs | 15.532μs | 16.100μs | 0.301μs | ±1.94% |
| benchDynamicRoutes | GET Method,Host,Worst Case     | 4.616mb  | 77.806μs | 74.620μs | 77.792μs | 80.740μs | 2.068μs | ±2.66% |
| benchDynamicRoutes | GET Method,Invalid Method      | 4.616mb  | 5.124μs  | 4.880μs  | 5.076μs  | 5.180μs  | 0.105μs | ±2.07% |
| benchDynamicRoutes | ALL Methods,Invalid Method     | 4.618mb  | 5.234μs  | 5.070μs  | 5.200μs  | 5.260μs  | 0.069μs | ±1.33% |
| benchDynamicRoutes | GET Method,Host,Invalid Method | 4.616mb  | 84.718μs | 82.460μs | 86.244μs | 89.540μs | 2.796μs | ±3.24% |
| benchOtherRoutes   | GET Method,Non Existent        | 4.616mb  | 2.912μs  | 2.880μs  | 2.936μs  | 3.030μs  | 0.052μs | ±1.77% |
| benchOtherRoutes   | ALL Methods,Non Existent       | 4.618mb  | 3.259μs  | 3.110μs  | 3.254μs  | 3.390μs  | 0.091μs | ±2.79% |
| benchOtherRoutes   | GET Method,Host,Non Existent   | 4.616mb  | 62.880μs | 60.720μs | 63.544μs | 66.440μs | 2.012μs | ±3.17% |
+--------------------+--------------------------------+----------+----------+----------+----------+----------+---------+--------+

SymfonyRouterCached
+--------------------+--------------------------------+----------+-----------+-----------+-----------+-----------+---------+--------+
| subject            | set                            | mem_peak | mode      | best      | mean      | worst     | stdev   | rstdev |
+--------------------+--------------------------------+----------+-----------+-----------+-----------+-----------+---------+--------+
| benchStaticRoutes  | GET Method,Best Case           | 6.719mb  | 0.945μs   | 0.910μs   | 0.938μs   | 0.950μs   | 0.015μs | ±1.57% |
| benchStaticRoutes  | ALL Methods,Best Case          | 6.721mb  | 3.884μs   | 3.850μs   | 3.912μs   | 3.990μs   | 0.052μs | ±1.32% |
| benchStaticRoutes  | GET Method,Host,Best Case      | 6.720mb  | 1.079μs   | 1.070μs   | 1.090μs   | 1.110μs   | 0.017μs | ±1.54% |
| benchStaticRoutes  | GET Method,Average Case        | 6.719mb  | 0.699μs   | 0.690μs   | 0.702μs   | 0.720μs   | 0.010μs | ±1.40% |
| benchStaticRoutes  | ALL Methods,Average Case       | 6.721mb  | 3.499μs   | 3.290μs   | 3.462μs   | 3.600μs   | 0.105μs | ±3.04% |
| benchStaticRoutes  | GET Method,Host,Average Case   | 6.720mb  | 0.959μs   | 0.920μs   | 0.954μs   | 0.980μs   | 0.021μs | ±2.16% |
| benchStaticRoutes  | GET Method,Worst Case          | 6.719mb  | 0.745μs   | 0.740μs   | 0.760μs   | 0.790μs   | 0.021μs | ±2.76% |
| benchStaticRoutes  | ALL Methods,Worst Case         | 6.721mb  | 3.863μs   | 3.800μs   | 3.912μs   | 4.050μs   | 0.090μs | ±2.31% |
| benchStaticRoutes  | GET Method,Host,Worst Case     | 6.720mb  | 0.865μs   | 0.860μs   | 0.872μs   | 0.900μs   | 0.015μs | ±1.69% |
| benchStaticRoutes  | GET Method,Invalid Method      | 6.720mb  | 36.773μs  | 35.490μs  | 36.520μs  | 36.970μs  | 0.530μs | ±1.45% |
| benchStaticRoutes  | ALL Methods,Invalid Method     | 6.722mb  | 32.881μs  | 32.040μs  | 33.122μs  | 34.540μs  | 0.836μs | ±2.53% |
| benchStaticRoutes  | GET Method,Host,Invalid Method | 6.720mb  | 44.817μs  | 44.340μs  | 45.184μs  | 46.010μs  | 0.671μs | ±1.49% |
| benchDynamicRoutes | GET Method,Best Case           | 6.719mb  | 1.418μs   | 1.330μs   | 1.398μs   | 1.440μs   | 0.040μs | ±2.84% |
| benchDynamicRoutes | ALL Methods,Best Case          | 6.721mb  | 6.701μs   | 6.580μs   | 6.796μs   | 7.030μs   | 0.167μs | ±2.46% |
| benchDynamicRoutes | GET Method,Host,Best Case      | 6.720mb  | 1.537μs   | 1.440μs   | 1.508μs   | 1.560μs   | 0.045μs | ±3.01% |
| benchDynamicRoutes | GET Method,Average Case        | 6.719mb  | 13.792μs  | 13.070μs  | 13.586μs  | 13.910μs  | 0.324μs | ±2.38% |
| benchDynamicRoutes | ALL Methods,Average Case       | 6.721mb  | 57.988μs  | 57.480μs  | 58.800μs  | 60.330μs  | 1.194μs | ±2.03% |
| benchDynamicRoutes | GET Method,Host,Average Case   | 6.720mb  | 16.632μs  | 16.520μs  | 16.870μs  | 17.440μs  | 0.362μs | ±2.14% |
| benchDynamicRoutes | GET Method,Worst Case          | 6.719mb  | 37.107μs  | 36.330μs  | 37.162μs  | 38.080μs  | 0.555μs | ±1.49% |
| benchDynamicRoutes | ALL Methods,Worst Case         | 6.721mb  | 166.268μs | 160.830μs | 167.080μs | 174.000μs | 4.484μs | ±2.68% |
| benchDynamicRoutes | GET Method,Host,Worst Case     | 6.720mb  | 47.514μs  | 45.390μs  | 47.084μs  | 47.760μs  | 0.875μs | ±1.86% |
| benchDynamicRoutes | GET Method,Invalid Method      | 6.720mb  | 90.960μs  | 86.030μs  | 89.990μs  | 92.590μs  | 2.273μs | ±2.53% |
| benchDynamicRoutes | ALL Methods,Invalid Method     | 6.722mb  | 96.603μs  | 94.860μs  | 97.972μs  | 102.410μs | 2.669μs | ±2.72% |
| benchDynamicRoutes | GET Method,Host,Invalid Method | 6.720mb  | 115.998μs | 111.010μs | 115.218μs | 118.420μs | 2.469μs | ±2.14% |
| benchOtherRoutes   | GET Method,Non Existent        | 6.720mb  | 25.533μs  | 24.790μs  | 25.824μs  | 26.810μs  | 0.734μs | ±2.84% |
| benchOtherRoutes   | ALL Methods,Non Existent       | 6.722mb  | 21.622μs  | 20.900μs  | 21.938μs  | 22.950μs  | 0.768μs | ±3.50% |
| benchOtherRoutes   | GET Method,Host,Non Existent   | 6.720mb  | 33.683μs  | 31.660μs  | 33.008μs  | 34.010μs  | 0.955μs | ±2.89% |
+--------------------+--------------------------------+----------+-----------+-----------+-----------+-----------+---------+--------+

FastRouteCached
+--------------------+----------------------------+----------+----------+----------+----------+----------+---------+--------+
| subject            | set                        | mem_peak | mode     | best     | mean     | worst    | stdev   | rstdev |
+--------------------+----------------------------+----------+----------+----------+----------+----------+---------+--------+
| benchStaticRoutes  | GET Method,Best Case       | 6.516mb  | 0.251μs  | 0.250μs  | 0.258μs  | 0.270μs  | 0.010μs | ±3.80% |
| benchStaticRoutes  | ALL Methods,Best Case      | 6.518mb  | 0.911μs  | 0.890μs  | 0.924μs  | 0.970μs  | 0.029μs | ±3.11% |
| benchStaticRoutes  | GET Method,Average Case    | 6.516mb  | 0.250μs  | 0.240μs  | 0.246μs  | 0.250μs  | 0.005μs | ±1.99% |
| benchStaticRoutes  | ALL Methods,Average Case   | 6.518mb  | 1.135μs  | 1.080μs  | 1.132μs  | 1.180μs  | 0.032μs | ±2.82% |
| benchStaticRoutes  | GET Method,Worst Case      | 6.516mb  | 0.250μs  | 0.250μs  | 0.250μs  | 0.250μs  | 0.000μs | ±0.00% |
| benchStaticRoutes  | ALL Methods,Worst Case     | 6.518mb  | 0.905μs  | 0.900μs  | 0.912μs  | 0.940μs  | 0.015μs | ±1.61% |
| benchStaticRoutes  | GET Method,Invalid Method  | 6.516mb  | 7.120μs  | 7.060μs  | 7.166μs  | 7.350μs  | 0.103μs | ±1.44% |
| benchStaticRoutes  | ALL Methods,Invalid Method | 6.518mb  | 9.133μs  | 8.710μs  | 9.008μs  | 9.240μs  | 0.200μs | ±2.22% |
| benchDynamicRoutes | GET Method,Best Case       | 6.516mb  | 0.725μs  | 0.720μs  | 0.740μs  | 0.770μs  | 0.021μs | ±2.83% |
| benchDynamicRoutes | ALL Methods,Best Case      | 6.518mb  | 3.335μs  | 3.320μs  | 3.376μs  | 3.480μs  | 0.063μs | ±1.88% |
| benchDynamicRoutes | GET Method,Average Case    | 6.516mb  | 2.883μs  | 2.850μs  | 2.912μs  | 3.020μs  | 0.060μs | ±2.05% |
| benchDynamicRoutes | ALL Methods,Average Case   | 6.518mb  | 16.440μs | 16.240μs | 16.628μs | 17.120μs | 0.336μs | ±2.02% |
| benchDynamicRoutes | GET Method,Worst Case      | 6.516mb  | 5.713μs  | 5.590μs  | 5.752μs  | 5.980μs  | 0.126μs | ±2.20% |
| benchDynamicRoutes | ALL Methods,Worst Case     | 6.518mb  | 29.339μs | 28.920μs | 29.786μs | 30.800μs | 0.742μs | ±2.49% |
| benchDynamicRoutes | GET Method,Invalid Method  | 6.516mb  | 31.913μs | 30.920μs | 32.068μs | 33.360μs | 0.804μs | ±2.51% |
| benchDynamicRoutes | ALL Methods,Invalid Method | 6.518mb  | 27.376μs | 26.850μs | 27.960μs | 29.230μs | 0.963μs | ±3.45% |
| benchOtherRoutes   | GET Method,Non Existent    | 6.516mb  | 7.470μs  | 7.280μs  | 7.518μs  | 7.750μs  | 0.169μs | ±2.25% |
| benchOtherRoutes   | ALL Methods,Non Existent   | 6.518mb  | 8.169μs  | 7.720μs  | 8.058μs  | 8.340μs  | 0.236μs | ±2.93% |
+--------------------+----------------------------+----------+----------+----------+----------+----------+---------+--------+
```

[PHP]: https://php.net
[Composer]: https://getcomposer.org
[GitHub Action]: https://github.com/divineniiquaye/php-routers-benchmarks/runs/2997693508?check_suite_focus=true
