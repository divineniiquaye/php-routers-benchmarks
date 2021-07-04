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
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Loader\ClosureLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;

/**
 * Groups(['symfony', 'raw'])
 */
class SymfonyRouter extends AbstractRouter
{
    protected Router $router;

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
        yield 'Best Case' => ['route' => '/abcbar/0'];

        yield 'Average Case' => ['route' => '/abcbar/199'];

        yield 'Worst Case' => ['route' => '/abcbar/399'];

        yield 'Invalid Method' => ['invalid' => self::INVALID_METHOD, 'route' => '/abcbar/399', 'result' => 405];
    }

    /**
     * {@inheritdoc}
     */
    public function provideOtherScenarios(): iterable
    {
        yield 'Non Existent' => ['invalid' => self::SINGLE_METHOD, 'route' => '/testing', 'result' => 404];
    }

    /**
     * {@inheritdoc}
     */
    public function createDispatcher(): void
    {
        $resource = static function (): RouteCollection {
            $collection = new RouteCollection();

            for ($i = 0; $i < 400; ++$i) {
                $collection->add('static_' . $i, new Route('/abc' . $i, [], [], [], '', [], self::ALL_METHODS));
                $collection->add('not_static_' . $i, new Route('/abc{foo}/' . $i, [], [], [], '', [], self::ALL_METHODS));

                $collection->add('static_host_' . $i, new Route('/host/abc' . $i, [], [], [], self::DOMAIN, [], self::ALL_METHODS));
                $collection->add('not_static_host_' . $i, new Route('/host/abc{foo}/' . $i, [], [], [], self::DOMAIN, [], self::ALL_METHODS));
            }

            return $collection;
        };

        $this->router = new Router(new ClosureLoader(), $resource);
    }

    /**
     * {@inheritdoc}
     */
    protected function runScenario(array $params): void
    {
        if (isset($params['domain'])) {
            $this->router->getContext()->setHost($params['domain']);
        }

        if (isset($params['invalid']) || \is_string($params['method'])) {
            $this->router->getContext()->setMethod($params['invalid'] ?? $params['method']);

            try {
                $result = $this->router->match($params['route']);

                \assert(!empty($result));
            } catch (MethodNotAllowedException | ResourceNotFoundException $e) {
                \assert($params['result'] === $e->getCode()); // If method does not match ...
            }
        } else {
            foreach ($params['method'] as $method) {
                $this->router->getContext()->setMethod($method);
                $result = $this->router->match($params['route']);

                \assert(!empty($result));
            }
        }
    }
}
