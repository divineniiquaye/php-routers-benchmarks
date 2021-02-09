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
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\UriFactory;
use Psr\Http\Message\ResponseFactoryInterface;
use Spiral\Core\Container;
use Spiral\Router\Exception\RouteNotFoundException;
use Spiral\Router\Route;
use Spiral\Router\Router;
use Spiral\Router\RouterInterface;
use Spiral\Router\UriHandler;

class SpiralRouter extends AbstractRouter
{
    public const PATH = '<world>';

    protected RouterInterface $router;

    /**
     * {@inheritdoc}
     */
    public function testStatic(): bool
    {
        $methods = $this->generator->getMethods();

        foreach ($methods as $method) {
            $path = ($this->strategy)($method);

            try {
                $this->router->handle(new ServerRequest([], [], $path, $method));
            } catch (RouteNotFoundException $e) {
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
                $this->router->handle(new ServerRequest([], [], $path . 'spiral_router', $method));
            } catch (RouteNotFoundException $e) {
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
        $container = new Container();
        $container->bind(ResponseFactoryInterface::class, new ResponseFactory);

        $router = new Router('/', new UriHandler(new UriFactory), $container);

        foreach ($routes as $route) {

            $spRoute = new Route($route['pattern'], fn () => 'Hello World');
            $spRoute->withVerbs(...$route['methods']);

            $router->setRoute($route['name'], $spRoute);
        }

        $this->router = $router;
    }
}
