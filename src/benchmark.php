<?php

declare(strict_types=1);

/*
 * This file is part of DivineNii opensource projects.
 *
 * PHP version 7.4 and above required
 *
 * @author    Divine Niiquaye Ibok <divineibok@gmail.com>
 * @copyright 2019 DivineNii (https://divinenii.com/)
 * @license   https://opensource.org/licenses/BSD-3-Clause License
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\BenchMark;

use App\BenchMark\Strategy\CaseInterface;

require __DIR__ . '/bootstrap.php';

// Add Routers For Benchmarking
$routers = [
    'Symfony'        => Routers\SymfonyRouter::class,
    'Flight Routing' => Routers\FlightRouting::class,
    'FastRoute'      => Routers\FastRoute::class,
    'Laravel'        => Routers\LaravelRouter::class,
    'AltoRouter'     => Routers\AltRouter::class,
    'AuraRouter'     => Routers\AuraRouter::class,
    'RareloopRouter' => Routers\RareloopRouter::class,
    'NetteRouter'    => Routers\NetteRouter::class,
    'SunriseRouter'  => Routers\SunriseRouter::class,
    'SpiralRouter'   => Routers\SpiralRouter::class,
    //'PeceeRouter'    => Routers\PeceeRouter::class,
    //'BramusRouter'   => Routers\BramusRouter::class,
];

// This generates routes for router and matches them.
$routerGenerator = static function (array $config, CaseInterface $strategy, string $router) {
    [$type, $isolated, $nbRoutes, $nbHosts, $cache] = $config;

    $generator = new RouteGenerator($isolated, $nbRoutes, $nbHosts);

    if ($type === 'SubDomain') {
        $generator->setHost($router::HOST);
        $generator->setTemplate($router::PATH, ['world' => '[^/]+']);
    } elseif ($type === 'Path') {
        $generator->setTemplate($router::PATH, ['world' => '[^/]+']);
    }

    [$ids, $routes] = $generator->generate($type === 'Static');

    /** @var \App\BenchMark\AbstractRouter $router */
    $router = new $router($strategy, $generator, $type, $cache);
    $method = 'test' . $type;

    $router->buildRoutes($routes);
    $strategy->add($ids);

    return $router->{$method}();
};

// Print out system and PHP Info
echo Reporter\BenchMark::systemInfo();

// Start BenchMarking and produce outcome
foreach ($modes as $title => $isolated) {
    echo Reporter\BenchMark::title($title, '#');
    $config = [];

    if ('With Routes Supporting All HTTP Methods And Cache' === $title) {
        $config['cache'] = true;
    }

    foreach ($benchmarks as $bench) {
        static $strategy;

        foreach ($bench['forms'] as $title => $variables) {
            foreach ($variables as $key => $value) {
                if ('strategy' === $key) {
                    $strategy = new $value();

                    continue;
                }
                $config[$key] = $value;
            }

            $benchmark = new Reporter\BenchMark();
            echo Reporter\BenchMark::title($title);
            $benchmark->repeat(100);

            foreach ($routers as $name => $router) {
                if (isset($config['cache']) && !$router::isCacheable()) {
                    continue;
                }

                if (!\method_exists($router, 'test' . $bench['type'])) {
                    continue;
                }

                $data = [$bench['type'], $isolated, $bench['nbRoutes'], $config['nbHosts'] ?? 1, isset($config['cache'])];

                $benchmark->run($name, $routerGenerator, $data, $strategy, $router);

                \gc_collect_cycles();
            }

            echo $benchmark->report();
        }
    }
}

// Remove cache directory after benchmark.
if (__DIR__ . '/caches') {
    \rmdir(__DIR__ . '/caches');
}
