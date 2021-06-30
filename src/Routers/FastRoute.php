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
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;

class FastRoute extends AbstractRouter
{
    protected const DOMAIN = null;

    protected Dispatcher $dispatcher;

    /**
     * {@inheritdoc}
     */
    public function provideStaticRoutes(): iterable
    {
        yield 'Best Case' => ['route' => '/abc0', 'result' => Dispatcher::FOUND];

        yield 'Average Case' => ['route' => '/abc199', 'result' => Dispatcher::FOUND];

        yield 'Worst Case' => ['route' => '/abc399', 'result' => Dispatcher::FOUND];

        yield 'Invalid Method' => ['invalid' => self::INVALID_METHOD, 'route' => '/abc399', 'result' => Dispatcher::METHOD_NOT_ALLOWED];
    }

    /**
     * {@inheritdoc}
     */
    public function provideDynamicRoutes(): iterable
    {
        yield 'Best Case' => ['route' => '/abcbar/0', 'result' => Dispatcher::FOUND];

        yield 'Average Case' => ['route' => '/abcbar/199', 'result' => Dispatcher::FOUND];

        yield 'Worst Case' => ['route' => '/abcbar/399','result' => Dispatcher::FOUND];
    }

    /**
     * {@inheritdoc}
     */
    public function provideOtherScenarios(): iterable
    {
        yield 'Non Existent' => ['invalid' => self::SINGLE_METHOD, 'route' => '/testing', 'result' => Dispatcher::NOT_FOUND];
    }

    /**
     * {@inheritdoc}
     */
    public function createDispatcher(): void
    {
        $this->dispatcher = \FastRoute\simpleDispatcher(
            static function (RouteCollector $routes): void {
                for ($i = 0; $i < 400; ++$i) {
                    $routes->addRoute(self::ALL_METHODS, '/abc' . $i, ['name' => 'static_' . $i]);
                    $routes->addRoute(self::ALL_METHODS, '/abc{foo}/' . $i, ['name' => 'not_static_' . $i]);
                }
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function runScenario(array $params): void
    {
        if (isset($params['invalid']) || \is_string($params['method'])) {
            $result = $this->dispatcher->dispatch($params['invalid'] ?? $params['method'], $params['route']);
            \assert($params['result'] === $result[0]);
        } else {
            foreach ($params['method'] as $method) {
                $result = $this->dispatcher->dispatch($method, $params['route']);
                \assert($params['result'] === $result[0]);
            }
        }
    }
}
