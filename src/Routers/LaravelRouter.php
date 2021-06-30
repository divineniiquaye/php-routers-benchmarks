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
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LaravelRouter extends AbstractRouter
{
    private Router $router;

    /**
     * {@inheritDoc}
     */
    public function provideStaticRoutes(): iterable
    {
        yield 'Best Case' => ['route' => '/abc0', 'result' => 'Laravel'];

        yield 'Average Case' => ['route' => '/abc199', 'result' => 'Laravel'];

        yield 'Worst Case' => ['route' => '/abc399', 'result' => 'Laravel'];

        yield 'Invalid Method' => ['invalid' => self::INVALID_METHOD, 'route' => '/abc399', 'result' => false];
    }

    /**
     * {@inheritdoc}
     */
    public function provideDynamicRoutes(): iterable
    {
        yield 'Best Case' => ['route' => '/abcbar/0', 'result' => 'Laravel'];

        yield 'Average Case' => ['route' => '/abcbar/199', 'result' => 'Laravel'];

        yield 'Worst Case' => ['route' => '/abcbar/399', 'result' => 'Laravel'];

        yield 'Invalid Method' => ['invalid' => self::INVALID_METHOD, 'route' => '/abcbar/399', 'result' => false];
    }

    /**
     * {@inheritdoc}
     */
    public function provideOtherScenarios(): iterable
    {
        yield 'Non Existent' => ['invalid' => self::SINGLE_METHOD, 'route' => '/testing', 'result' => false];
    }

    /**
     * {@inheritdoc}
     */
    public function createDispatcher(): void
    {
        $router = new Router(new Dispatcher(), new Container());

        for ($i = 0; $i < 400; ++$i) {
            $router->addRoute(self::ALL_METHODS, '/abc' . $i, fn () => 'Laravel')->name('static_' . $i);
            $router->addRoute(self::ALL_METHODS, '/abc{foo}/' . $i, fn () => 'Laravel')->name('not_static_' . $i);

            $router->addRoute(self::ALL_METHODS, '/host/abc' . $i, fn () => 'Laravel')->domain(self::DOMAIN)->name('static_host_' . $i);
            $router->addRoute(self::ALL_METHODS, '/host/abc{foo}/' . $i, fn () => 'Laravel')->domain(self::DOMAIN)->name('not_static_host_' . $i);
        }

        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    protected function runScenario(array $params): void
    {
        $path = (isset($params['domain']) ? '//' . $params['domain'] . '/host' : '') . $params['route'];

        if (isset($params['invalid']) || \is_string($params['method'])) {
            try {
                $result = $this->router->dispatch(Request::create($path, $params['invalid'] ?? $params['method']))->getContent();
                \assert($params['result'] === $result);
            } catch (MethodNotAllowedHttpException | NotFoundHttpException $e) {
                \assert(false === $params['result']); // If method does not match ...
            }
        } else {
            foreach ($params['method'] as $method) {
                $result = $this->router->dispatch(Request::create($path, $method))->getContent();
                \assert($params['result'] === $result);
            }
        }
    }
}
