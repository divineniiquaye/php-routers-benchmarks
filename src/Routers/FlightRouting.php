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
use Flight\Routing\Exceptions\RouteNotFoundException;
use Flight\Routing\Interfaces\RouteMatcherInterface;
use Flight\Routing\Matchers\SimpleRouteMatcher;
use Flight\Routing\RouteCollection;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;

class FlightRouting extends AbstractRouter
{
    protected RouteMatcherInterface $router;

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
                $this->router->match(new ServerRequest([], [], $path . 'flight_routing', $method));
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
                $uri = new Uri($path . 'flight_routing');

                if ($host !== '*') {
                    $uri = $uri->withHost($host . 'flight_routing');
                }

                try {
                    $this->router->match(new ServerRequest([], [], $uri, $method));
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
        $frCollection = new RouteCollection(false);

        foreach ($routes as $route) {
            $frRoute = $frCollection->addRoute($route['pattern'], $route['methods'])
                ->bind($route['name'])->asserts($route['constraints']);

            if ('*' !== $route['host']) {
                $frRoute->domain($route['host']);
            }
        }

        $this->router = new SimpleRouteMatcher($frCollection);
    }
}
