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

use AltoRouter;
use App\BenchMark\AbstractRouter;

class AltRouter extends AbstractRouter
{
    protected const PATH = '[a:action]';

    /**
     * {@inheritdoc}
     */
    public function testStatic(): bool
    {
        /** @var AltoRouter $router */
        list($router, $strategy) = $this->getStrategy(true);

        foreach ($this->generator->getMethods() as $method) {
            [, $path] = $strategy($method);

            if (!$router->match($path, $method)) {
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

        /** @var AltoRouter $router */
        list($router, $strategy) = $this->getStrategy();

        foreach ($this->generator->getMethods() as $method) {
            [, $path] = $strategy($method);

            if (!$router->match($path . 'altorouter', $method)) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function buildRoutes(array $routes): AltoRouter
    {
        $router = new AltoRouter();

        foreach ($routes as $route) {
            foreach ($route['methods'] as $method) {
                $router->map($method, $route['pattern'], fn () => 'Hello');
            }
        }

        return $router;
    }
}
