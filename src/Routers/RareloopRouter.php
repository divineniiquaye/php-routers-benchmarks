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
use Exception;
use Laminas\Diactoros\ServerRequest;
use Rareloop\Router\Router;

class RareloopRouter extends AbstractRouter
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

            try {
                $router->match(new ServerRequest([], [], $path, $method));
            } catch (Exception $e) {
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
        $this->generator->setTemplate(self::PATH, ['world' => '[^/]+']);

        /** @var Router $router */
        list($router, $strategy) = $this->getStrategy();

        foreach ($this->generator->getMethods() as $method) {
            [, $path] = $strategy($method);

            try {
                $router->match(new ServerRequest([], [], $path . 'rareloop_router', $method));
            } catch (Exception $e) {
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
            $router->map($route['methods'], $route['pattern'], fn () => 'Hello')
                ->where($route['constraints']);
        }

        return $router;
    }
}
