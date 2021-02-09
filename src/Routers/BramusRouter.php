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
use Bramus\Router\Router;

class BramusRouter extends AbstractRouter
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

            $_SERVER['REQUEST_METHOD'] = $method;
            $_SERVER['SCRIPT_NAME'] = '';
            $_SERVER['REQUEST_URI'] = $path;

            if (!$this->router->run($method, $path)) {
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

            $_SERVER['REQUEST_METHOD'] = $method;
            $_SERVER['SCRIPT_NAME'] = '';
            $_SERVER['REQUEST_URI'] = $path;

            if (!$this->router->run($method, $path . 'bramus_router')) {
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
            foreach ($route['methods'] as $method) {
                $router->match($method, $route['pattern'], fn () => 'Hello');
            }
        }

        $this->router = $router;
    }
}
