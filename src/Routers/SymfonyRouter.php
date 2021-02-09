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
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Loader\ClosureLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;

class SymfonyRouter extends AbstractRouter
{
    protected Router $router;

    /**
     * {@inheritdoc}
     */
    public static function isCacheable(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function testStatic(): bool
    {
        $methods = $this->generator->getMethods();

        foreach ($methods as $method) {
            $path = ($this->strategy)($method);
            $this->router->getContext()->setMethod($method);

            try {
                $this->router->match($path);
            } catch (ResourceNotFoundException $e) {
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

            $this->router->getContext()->setMethod($method);

            try {
                $this->router->match($path . 'symfony');
            } catch (ResourceNotFoundException $e) {
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
        $router = clone $this->router;

        foreach ($hosts as $host) {
            $methods = $this->generator->getMethods();

            foreach ($methods as $method) {
                $path = ($this->strategy)($method, $host);

                if ($host !== '*') {
                    $router->getContext()->setHost($host . 'symfony');
                }
                $router->getContext()->setMethod($method);

                try {
                    $router->match($path . 'symfony');
                } catch (ResourceNotFoundException $e) {
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
        $resource = static function () use ($routes): RouteCollection {
            $sfCollection = new RouteCollection();

            foreach ($routes as $route) {
                $sfRoute = new Route($route['pattern']);

                if ($route['host'] !== '*') {
                    $sfRoute->setHost($route['host']);
                }
                $sfRoute->setMethods($route['methods']);
                $sfRoute->setRequirements($route['constraints']);

                $sfCollection->add($route['pattern'], $sfRoute);
            }

            return $sfCollection;
        };

        $router = new Router(new ClosureLoader(), $resource);

        if (null !== $cacheDir = $this->getCache('symfony')) {
            $router->setOption('cache_dir', $cacheDir);
        }

        $this->router = $router;
    }
}
