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

use App\BenchMark\AbstractRouter;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;

class FastRoute extends AbstractRouter
{
    protected Dispatcher $router;

    /**
     * {@inheritdoc}
     */
    public static function isCacheable(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function testStatic(): bool
    {
        $methods = $this->generator->getMethods();

        foreach ($methods as $method) {
            $path = ($this->strategy)($method);

            $result = $this->router->dispatch($method, $path);

            if ($result[0] !== $this->router::FOUND) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function testPath(): bool
    {
        $methods = $this->generator->getMethods();

        foreach ($methods as $method) {
            $path = ($this->strategy)($method);

            $result = $this->router->dispatch($method, $path . 'fastroute');

            if ($result[0] !== $this->router::FOUND) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function buildRoutes(array $routes): void
    {
        $routes = function (RouteCollector $router) use ($routes): void {
            foreach ($routes as $route) {
                foreach ($route['methods'] as $method) {
                    $router->addRoute($method, $route['pattern'], 'phpinfo');
                }
            }
        };

        if (null !== $cacheDir = $this->getCache('fastroute')) {
            if (!file_exists($cacheDir)) {
                mkdir($cacheDir, 0777, true);
            }

            $router = \FastRoute\cachedDispatcher($routes, ['cacheFile' => $cacheDir . '/compiled.php']);
        } else {
            $router = \FastRoute\simpleDispatcher($routes);
        }

        $this->router = $router;
    }
}
