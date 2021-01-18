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
use Symfony\Component\Routing\RouterInterface;

class SymfonyRouter extends AbstractRouter
{
    /**
     * {@inheritdoc}
     */
    public function testStatic(): bool
    {
        /** @var RouterInterface $router */
        list($router, $strategy) = $this->getStrategy(true);

        foreach ($this->generator->getMethods() as $method) {
            [$id, $path] = $strategy($method);

            $router->getContext()->setMethod($method);
            $params = $router->match($path);

            try {
                $params = $router->match($path);

                if (($params['id'] ?? null) !== $id) {
                    return false;
                }
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
        $this->generator->setTemplate(self::PATH, ['world' => '[^/]+']);

        /** @var RouterInterface $router */
        list($router, $strategy) = $this->getStrategy();

        foreach ($this->generator->getMethods() as $method) {
            [$id, $path] = $strategy($method);

            $router->getContext()->setMethod($method);

            try {
                $params = $router->match($path . 'symfony');

                if (($params['id'] ?? null) !== $id) {
                    return false;
                }
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
        $this->generator->setHost(self::HOST);
        $this->generator->setTemplate(self::PATH, ['world' => '[^/]+']);

        /** @var RouterInterface $router */
        list($router, $strategy) = $this->getStrategy(false, true);

        foreach ($this->generator->getHosts() as $host) {
            foreach ($this->generator->getMethods() as $method) {
                [$id, $path] = $strategy($method, $host);

                if ($host !== '*') {
                    $router->getContext()->setHost($host . 'symfony');
                }
                $router->getContext()->setMethod($method);

                try {
                    $params = $router->match($path . 'symfony');

                    if (($params['id'] ?? null) !== $id) {
                        return false;
                    }
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
    protected function buildRoutes(array $routes): RouterInterface
    {
        $resource = static function () use ($routes): RouteCollection {
            $sfCollection = new RouteCollection();

            foreach ($routes as $route) {
                $sfRoute = new Route($route['pattern'], ['id' => $route['name']]);

                if ($route['host'] !== '*') {
                    $sfRoute->setHost($route['host']);
                }

                $sfRoute->setMethods($route['methods']);
                $sfRoute->setRequirements($route['constraints']);
                $sfCollection->add($route['pattern'], $sfRoute);
            }

            return $sfCollection;
        };

        return new Router(new ClosureLoader(), $resource);
    }
}
