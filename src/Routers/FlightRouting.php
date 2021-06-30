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
use Flight\Routing\Exceptions\MethodNotAllowedException;
use Flight\Routing\Interfaces\RouteMatcherInterface;
use Flight\Routing\Route;
use Flight\Routing\RouteCollection;
use Flight\Routing\RouteMatcher;
use Laminas\Diactoros\Uri;

class FlightRouting extends AbstractRouter
{
    protected RouteMatcherInterface $router;

    /**
     * {@inheritdoc}
     */
    public function provideStaticRoutes(): iterable
    {
        yield 'Best Case' => ['route' => '/abc0'];

        yield 'Average Case' => ['route' => '/abc199'];

        yield 'Worst Case' => ['route' => '/abc399'];

        yield 'Invalid Method' => ['invalid' => self::INVALID_METHOD, 'route' => '/abc399', 'result' => 405];
    }

    /**
     * {@inheritdoc}
     */
    public function provideDynamicRoutes(): iterable
    {
        yield 'Best Case' => ['route' => '/abcbar_foo-path/0'];

        yield 'Average Case' => ['route' => '/abcbar_foo-path/199'];

        yield 'Worst Case' => ['route' => '/abcbar_foo-path/399'];
    }

    /**
     * {@inheritdoc}
     */
    public function provideOtherScenarios(): iterable
    {
        yield 'Invalid Method' => ['invalid' => self::INVALID_METHOD, 'route' => '/abcbar_foo-path/399', 'result' => 405];

        yield 'Non Existent' => ['invalid' => self::SINGLE_METHOD, 'route' => '/testing', 'result' => false];
    }

    /**
     * {@inheritdoc}
     */
    public function createDispatcher(): void
    {
        $collection = new RouteCollection();

        for ($i = 0; $i < 400; ++$i) {
            $collection->addRoute('/abc' . $i, self::ALL_METHODS)->bind('static_' . $i);
            $collection->addRoute('/abc{foo}_{bar}-{baz}/' . $i, self::ALL_METHODS)->bind('not_static_' . $i);

            $collection->addRoute('//' . self::DOMAIN . '/host/abc' . $i, self::ALL_METHODS)->bind('static_host_' . $i);
            $collection->addRoute('//' . self::DOMAIN . '/host/abc{foo}_{bar}-{path}/' . $i, self::ALL_METHODS)->bind('not_static_host_' . $i);
        }

        $this->router = new RouteMatcher($collection);
    }

    /**
     * {@inheritdoc}
     */
    protected function runScenario(array $params): void
    {
        $uri = new Uri((isset($params['domain']) ? '//' . $params['domain'] . '/host' : '') . $params['route'] . '');

        if (isset($params['invalid']) || \is_string($params['method'])) {
            try {
                $result = $this->router->match($params['invalid'] ?? $params['method'], $uri);

                \assert(isset($params['result']) ? null === $result : $result instanceof Route);
            } catch (MethodNotAllowedException $e) {
                \assert($params['result'] === $e->getCode()); // If method does not match ...
            }
        } else {
            foreach ($params['method'] as $method) {
                $result = $this->router->match($method, $uri);

                \assert($result instanceof Route);
            }
        }
    }
}
