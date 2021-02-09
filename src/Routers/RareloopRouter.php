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
use Laminas\Diactoros\ServerRequest;
use Rareloop\Router\Router;

class RareloopRouter extends AbstractRouter
{
    protected Router $router;

    /**
     * {@inheritdoc}
     */
    public function testStatic(): bool
    {
        $methods = $this->generator->getMethods();

        foreach ($methods as $method) {
            $path = ($this->strategy)($method);

            try {
                $this->router->match(new ServerRequest([], [], $path, $method));
            } catch (\Exception $e) {
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

            try {
                $this->router->match(new ServerRequest([], [], $path . 'rareloop_router', $method));
            } catch (\Exception $e) {
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
        $router = new Router();

        foreach ($routes as $route) {
            $router->map($route['methods'], $route['pattern'], fn () => 'Hello')
                ->where($route['constraints']);
        }

        $this->router = $router;
    }
}
