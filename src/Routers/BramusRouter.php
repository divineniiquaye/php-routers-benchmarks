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
    /**
     * {@inheritdoc}
     */
    public function testStatic(): bool
    {
        /** @var Router $router */
        list($router, $strategy) = $this->getStrategy(true);

        foreach ($this->generator->getMethods() as $method) {
            [, $path] = $strategy($method);

            $_SERVER['REQUEST_METHOD'] = $method;
            $_SERVER['SCRIPT_NAME'] = '';
            $_SERVER['REQUEST_URI'] = $path;

            if (!$router->run($method, $path)) {
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

        /** @var Router $router */
        list($router, $strategy) = $this->getStrategy();

        foreach ($this->generator->getMethods() as $method) {
            [, $path] = $strategy($method);

            $_SERVER['REQUEST_METHOD'] = $method;
            $_SERVER['SCRIPT_NAME'] = '';
            $_SERVER['REQUEST_URI'] = $path;

            if (!$router->run($method, $path . 'bramus_router')) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function buildRoutes(array $routes): Router
    {
        $router = new Router();

        foreach ($routes as $route) {
            foreach ($route['methods'] as $method) {
                $router->match($method, $route['pattern'], fn () => 'Hello');
            }
        }

        return $router;
    }
}
