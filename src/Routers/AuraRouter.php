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
use Aura\Router\Matcher;
use Aura\Router\Route;
use Aura\Router\RouterContainer;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;

class AuraRouter extends AbstractRouter
{
    /**
     * {@inheritdoc}
     */
    public function testStatic(): bool
    {
        /** @var Matcher $router */
        list($router, $strategy) = $this->getStrategy(true);

        foreach ($this->generator->getMethods() as $method) {
            [, $path] = $strategy($method);
            $request     = new ServerRequest([], [], $path, $method);

            if (!$router->match($request) instanceof Route) {
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

        /** @var Matcher $router */
        list($router, $strategy) = $this->getStrategy();

        foreach ($this->generator->getMethods() as $method) {
            [, $path] = $strategy($method);
            $request     = new ServerRequest([], [], $path . 'aura_router', $method);

            if (!$router->match($request) instanceof Route) {
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
        $this->generator->setHost(self::HOST);
        $this->generator->setTemplate(self::PATH, ['world' => '[^/]+']);

        /** @var Matcher $router */
        list($router, $strategy) = $this->getStrategy(false, true);

        foreach ($this->generator->getHosts() as $host) {
            foreach ($this->generator->getMethods() as $method) {
                [, $path] = $strategy($method, $host);
                $uri = new Uri($path . 'aura_router');

                if ($host !== '*') {
                    $uri = $uri->withHost($host . 'aura');
                }

                $request = new ServerRequest([], [], $uri, $method);

                if (!$router->match($request) instanceof Route) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function buildRoutes(array $routes): Matcher
    {
        $routerContainer = new RouterContainer();
        $collection      = $routerContainer->getMap();

        foreach ($routes as $route) {
            $auraRoute = $collection->route($route['name'], $route['pattern'], fn () => 'Hello');

            if ($route['host'] !== '*') {
                $auraRoute->host($route['host']);
            }

            $auraRoute->tokens($route['constraints']);
            $auraRoute->allows($route['methods']);
        }

        return $routerContainer->getMatcher();
    }
}
