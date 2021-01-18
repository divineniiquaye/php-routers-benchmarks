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
    /**
     * {@inheritdoc}
     */
    public function testStatic(): bool
    {
        /** @var Dispatcher $router */
        list($router, $strategy) = $this->getStrategy(true);

        foreach ($this->generator->getMethods() as $method) {
            [, $path] = $strategy($method);

            $result = $router->dispatch($method, $path);

            if ($result[0] !== $router::FOUND) {
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
        $this->generator->setTemplate(self::PATH);

        /** @var Dispatcher $router */
        list($router, $strategy) = $this->getStrategy();

        foreach ($this->generator->getMethods() as $method) {
            [, $path] = $strategy($method);

            $result = $router->dispatch($method, $path . 'fastroute');

            if ($result[0] !== $router::FOUND) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function buildRoutes(array $routes): Dispatcher
    {
        $router = \FastRoute\simpleDispatcher(function (RouteCollector $router) use ($routes): void {
            foreach ($routes as $route) {
                foreach ($route['methods'] as $method) {
                    $router->addRoute($method, $route['pattern'], fn () => 'Hello');
                }
            }
        });

        return $router;
    }
}
