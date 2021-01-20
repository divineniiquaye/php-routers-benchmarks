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

class SunriseRouter extends AbstractRouter
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
                $router->handle((new ServerRequestFactory())->createServerRequest($method, $path));
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
        $this->generator->setTemplate(self::PATH, ['world' => '[^/]+']);

        /** @var Router $router */
        list($router, $strategy) = $this->getStrategy();

        foreach ($this->generator->getMethods() as $method) {
            [, $path] = $strategy($method);

            try {
                $router->handle((new ServerRequestFactory())->createServerRequest(
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
     * {@inheritdoc}
     */
    protected function buildRoutes(array $routes): Router
    {
        $router = new Router();

        foreach ($routes as $route) {
            $pattern = $route['pattern'];

            if ('*' !== $route['host']) {
                $pattern = $route['host'] . $pattern;
            }

            $frRoute = new Route(
                '_' . $route['name'],
                $pattern,
                (array) $route['methods'],
                new CallableRequestHandler(
                    function (ServerRequestInterface $request): ResponseInterface {
                        return (new ResponseFactory())->createJsonResponse(200, [
                            'status' => 'ok',
                            'method' => $request->getMethod(),
                        ]);
                    }
                )
            );

            $router->addRoute($frRoute);
        }

        return $router;
    }
}
