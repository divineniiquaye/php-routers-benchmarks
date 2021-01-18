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
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;

class LaravelRouter extends AbstractRouter
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

            $request = Request::create($path, $method);
            $result  = $router->dispatch($request)->getContent();

            if ($result !== 'Hello') {
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

            $request = Request::create($path . 'laravel', $method);
            $result  = $router->dispatch($request)->getContent();

            if ($result !== 'laravel') {
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

        /** @var Router $router */
        list($router, $strategy) = $this->getStrategy(false, true);

        foreach ($this->generator->getHosts() as $host) {
            foreach ($this->generator->getMethods() as $method) {
                [, $path] = $strategy($method, $host);
                $server   = [];

                if ($host !== '*') {
                    $server['HTTP_HOST'] = $host . 'laravel';
                }

                $request = Request::create($path . 'laravel', $method, [], [], [], $server);
                $result  = $router->dispatch($request)->getContent();

                if ($result !== 'laravel') {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function buildRoutes(array $routes): Router
    {
        $router = new Router(new Dispatcher(), new Container());

        foreach ($routes as $route) {
            $action = [
                'uses' => function ($id = 'Hello') {
                    return $id;
                },
            ];

            if ($route['host'] !== '*') {
                $action['domain'] = $route['host'];
            }

            $router->getRoutes()->add(
                new Route($route['methods'], $route['pattern'], $action)
            )->where($route['constraints']);
        }

        return $router;
    }
}
