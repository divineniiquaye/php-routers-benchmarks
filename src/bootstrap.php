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

require __DIR__ . '/../vendor/autoload.php';

$modes = [
    'With Routes Supporting All HTTP Methods'           => false,
    'With Routes Supporting Only A Single HTTP Methods' => true,
    'With Routes Supporting All HTTP Methods And Cache' => false,
];

$benchmarks = [
    [
        'type'       => 'Static',
        'nbRoutes'   => 300,
        'forms'      => [
            'Best Case (static-path)' => [
                'strategy' => App\BenchMark\Strategy\BestCase::class,
            ],
            'Average Case (static-path)' => [
                'strategy' => App\BenchMark\Strategy\AverageCase::class,
            ],
            'Worst Case (static-path)' => [
                'strategy' => App\BenchMark\Strategy\WorstCase::class,
            ],
        ],
    ],
    [
        'type'       => 'Path',
        'nbRoutes'   => 150,
        'forms'      => [
            'Best Case (dynamic-path)' => [
                'strategy' => App\BenchMark\Strategy\BestCase::class,
            ],
            'Average Case (dynamic-path)' => [
                'strategy' => App\BenchMark\Strategy\AverageCase::class,
            ],
            'Worst Case (dynamic-path)' => [
                'strategy' => App\BenchMark\Strategy\WorstCase::class,
            ],
        ],
    ],
    [
        'type'     => 'SubDomain',
        'nbRoutes' => 10,
        'forms'    => [
            'Best Case (sub-domain)' => [
                'nbHosts'  => 10,
                'strategy' => App\BenchMark\Strategy\BestCase::class,
            ],
            'Average Case (sub-domain)' => [
                'nbHosts'  => 10,
                'strategy' => App\BenchMark\Strategy\AverageCase::class,
            ],
            'Worst Case (sub-domain)' => [
                'nbHosts'  => 10,
                'strategy' => App\BenchMark\Strategy\WorstCase::class,
            ],
        ],
    ],
];
