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

namespace App\BenchMark\Routers;

/**
 * @Groups({"fast-route", "cached"})
 */
class FastRouteCached extends FastRoute
{
    /**
     * {@inheritdoc}
     */
    public function createDispatcher(): void
    {
        $this->dispatcher = \FastRoute\cachedDispatcher(
            static function (\FastRoute\RouteCollector $routes): void {
                for ($i = 0; $i < 400; ++$i) {
                    $routes->addRoute(self::ALL_METHODS, '/abc' . $i, ['name' => 'static_' . $i]);
                    $routes->addRoute(self::ALL_METHODS, '/abc{foo}/' . $i, ['name' => 'not_static_' . $i]);
                }
            },
            ['cacheKey' => __DIR__ . '/../caches/fast-cached-routes.php']
        );
    }
}
