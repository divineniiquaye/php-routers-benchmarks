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
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sunrise\Http\Message\ResponseFactory;
use Sunrise\Http\Router\Exception\RouteNotFoundException;
use Sunrise\Http\Router\RequestHandler\CallableRequestHandler;
use Sunrise\Http\Router\Route;
use Sunrise\Http\Router\Router;
use Sunrise\Http\ServerRequest\ServerRequestFactory;
use Sunrise\Uri\Uri;

class SunriseRouter extends AbstractRouter
{
    public const HOST = '';

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
                $this->router->handle((new ServerRequestFactory())->createServerRequest($method, $path));
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
                $this->router->handle((new ServerRequestFactory())->createServerRequest(
                    $method,
                    $path . 'sunrise_router'
                ));
            } catch (RouteNotFoundException $e) {
                return false;
            }
        }

        return true;
    }

    /**
     * Test Sub Domain with route path
     *
     * @return bool
     */
    public function testSubDomain(): bool
    {
        $hosts = $this->generator->getHosts();

        foreach ($hosts as $host) {
            $methods = $this->generator->getMethods();

            foreach ($methods as $method) {
                $path = ($this->strategy)($method, $host);
                $uri = new Uri($path . 'sunrise_router');

                if ($host !== '*') {
                    $uri = $uri->withHost($host);
                }

                try {
                    $this->router->match((new ServerRequestFactory())->createServerRequest($method, $uri));
                } catch (RouteNotFoundException $e) {
                    return false;
                }
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
            $srRoute = new Route(
                $route['name'],
                $route['pattern'],
                $route['methods'],
                new CallableRequestHandler(
                    function (ServerRequestInterface $request): ResponseInterface {
                        return (new ResponseFactory())->createJsonResponse(200, [
                            'status' => 'ok',
                            'method' => $request->getMethod(),
                        ]);
                    }
                )
            );

            if ('*' !== $route['host']) {
                $srRoute->setHost($route['host']);
            }

            $router->addRoute($srRoute);
        }

        $this->router = $router;
    }
}
