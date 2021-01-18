#!/usr/bin/env php
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

use ReflectionClass;

require __DIR__ . '/bootstrap.php';

// Add Routers For Benchmarking
$routers = [
    'Symfony'        => Routers\SymfonyRouter::class,
    'Flight Routing' => Routers\FlightRouting::class,
];

// Print out system and PHP Info
echo Reporter\BenchMark::systemInfo();

// Start BenchMarking and produce outcome
foreach ($modes as $title => $isolated) {
    echo Reporter\BenchMark::title($title, '#');
    $config = ['isolated' => $isolated];

    foreach ($benchmarks as $bench) {
        $type = $bench['type'];
        static $strategy;

        foreach ($bench['forms'] as $title => $variables) {
            foreach ($variables as $key => $value) {
                if ('strategy' === $key) {
                    $strategy = (new ReflectionClass($value))->newInstanceWithoutConstructor();

                    continue;
                }

                $config[$key] = $value;
            }

            $benchmark = new Reporter\BenchMark();
            echo Reporter\BenchMark::title($title);
            $benchmark->repeat(10);

            foreach ($routers as $name => $router) {
                $generator = new RouteGenerator();
                $generator->nbRoutes($bench['nbRoutes']);

                $routerReflection = new ReflectionClass($router);

                if ($routerReflection->hasMethod($method = 'test' . $type)) {
                    $router = $routerReflection->newInstanceArgs([$strategy, $generator, $config]);

                    $benchmark->run($name, [$router, $method]);
                }

                \gc_collect_cycles();
            }

            echo $benchmark->report();
        }
    }
}
