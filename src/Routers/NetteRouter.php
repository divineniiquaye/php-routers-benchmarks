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
use Nette\Http\Request;
use Nette\Http\UrlScript;
use Nette\Routing\RouteList;
use Nette\Routing\Router;

class NetteRouter extends AbstractRouter
{
    public const PATH = '<world>/<method>';

    public const HOST = '<extension>';

    protected Router $router;

    /**
     * {@inheritdoc}
     */
    public function testStatic(): bool
    {
        $methods = $this->generator->getMethods();

        foreach ($methods as $method) {
            $path    = ($this->strategy)($method);
            $request = new Request(new UrlScript($path), null, null, null, null, $method);
            $route   = $this->router->match($request);

            if (null === $route) {
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
            $request  = new Request(new UrlScript($path . 'nette' . $method), null, null, null, null, $method);

            if (null === $this->router->match($request)) {
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
                $uri      = new UrlScript($path . 'nette' . $method);

                if ($host !== '*') {
                    $uri = $uri->withHost($host . 'nette');
                }

                $request = new Request($uri, null, null, null, null, $method);

                if (null === $this->router->match($request)) {
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
        $router = new RouteList();

        foreach ($routes as $route) {
            if ('*' !== $route['host']) {
                $router->withDomain($route['host']);
            }

            foreach ($route['methods'] as $method) {
                $router->addRoute($route['pattern'], ['world' => 'Hello', 'method' => $method]);
            }
        }

        $this->router = $router;
    }
}
