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
use Aura\Router\Matcher;
use Aura\Router\Route;
use Aura\Router\RouterContainer;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;

/**
 * Groups(['aura-router', 'raw'])
 */
class AuraRouter extends AbstractRouter
{
    private Matcher $router;

    /**
     * {@inheritdoc}
     */
    public function provideStaticRoutes(): iterable
    {
        yield 'Best Case' => ['route' => '/abc0'];

        yield 'Average Case' => ['route' => '/abc199'];

        yield 'Worst Case' => ['route' => '/abc399'];

        yield 'Invalid Method' => ['invalid' => self::INVALID_METHOD, 'route' => '/abc399', 'result' => false];
    }

    /**
     * {@inheritdoc}
     */
    public function provideDynamicRoutes(): iterable
    {
        yield 'Best Case' => ['route' => '/abcbar/0'];

        yield 'Average Case' => ['route' => '/abcbar/199'];

        yield 'Worst Case' => ['route' => '/abcbar/399'];

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
        $routerContainer = new RouterContainer();
        $collection = $routerContainer->getMap();

        for ($i = 0; $i < 400; ++$i) {
            $collection->route('static_' . $i, '/abc' . $i, 'Alto')->allows(self::ALL_METHODS);
            $collection->route('not_static_' . $i, '/abc{foo}/' . $i, 'Alto')->allows(self::ALL_METHODS);

            $collection->route('static_host_' . $i, '/host/abc' . $i, 'Alto')->allows(self::ALL_METHODS)->host(self::DOMAIN);
            $collection->route('not_static_host_' . $i, '/host/abc{foo}/' . $i, 'Alto')->allows(self::ALL_METHODS)->host(self::DOMAIN);
        }

        $this->router = $routerContainer->getMatcher();
    }

    /**
     * {@inheritdoc}
     */
    protected function runScenario(array $params): void
    {
        $uri = new Uri((isset($params['domain']) ? '//' . $params['domain'] . '/host' : '') . $params['route']);

        if (isset($params['invalid']) || \is_string($params['method'])) {
            $request = new ServerRequest([], [], $uri, $params['invalid'] ?? $params['method']);
            $result = $this->router->match($request);

            \assert(isset($params['result']) ? $params['result'] === $result : $result instanceof Route);
        } else {
            foreach ($params['method'] as $method) {
                $request = new ServerRequest([], [], $uri, $method);
                $result = $this->router->match($request);

                \assert($result instanceof Route);
            }
        }
    }
}
