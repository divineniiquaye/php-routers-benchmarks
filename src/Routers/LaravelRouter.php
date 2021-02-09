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
use Illuminate\Routing\Router;

class LaravelRouter extends AbstractRouter
{
    protected Router $router;

    /**
     * {@inheritdoc}
     */
    public function testStatic(): bool
    {
        $methods = $this->generator->getMethods();

        foreach ($methods as $method) {
            $path = ($this->strategy)($method);

            $request = Request::create($path, $method);
            $result  = $this->router->dispatch($request)->getContent();

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
        $methods = $this->generator->getMethods();

        foreach ($methods as $method) {
            $path = ($this->strategy)($method);

            $request = Request::create($path . 'laravel', $method);
            $result  = $this->router->dispatch($request)->getContent();

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
        $hosts = $this->generator->getHosts();

        foreach ($hosts as $host) {
            $methods = $this->generator->getMethods();

            foreach ($methods as $method) {
                $path = ($this->strategy)($method, $host);
                $server   = [];

                if ($host !== '*') {
                    $server['HTTP_HOST'] = $host . 'laravel';
                }

                $request = Request::create($path . 'laravel', $method, [], [], [], $server);
                $result  = $this->router->dispatch($request)->getContent();

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
    public function buildRoutes(array $routes): void
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

            $router->addRoute($route['methods'], $route['pattern'], $action)
                ->setWheres($route['constraints']);
        }

        $this->router = $router;
    }
}
