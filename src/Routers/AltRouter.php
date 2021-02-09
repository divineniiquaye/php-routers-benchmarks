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
    public const PATH = '[a:action]';

    protected AltoRouter $router;

    /**
     * {@inheritdoc}
     */
    public function testStatic(): bool
    {
        $methods = $this->generator->getMethods();

        foreach ($methods as $method) {
            $path = ($this->strategy)($method);

            if (!$this->router->match($path, $method)) {
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

            if (!$this->router->match($path . 'altorouter', $method)) {
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
        $router = new AltoRouter();

        foreach ($routes as $route) {
            foreach ($route['methods'] as $method) {
                $router->map($method, $route['pattern'], fn () => 'Hello');
            }
        }

        $this->router = $router;
    }
}
