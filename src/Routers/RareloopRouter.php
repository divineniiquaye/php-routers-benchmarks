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
use Laminas\Diactoros\ServerRequest;
use Rareloop\Router\Router;

/**
 * Groups(['rareloop-router', 'raw'])
 */
class RareloopRouter extends AbstractRouter
{
    protected const DOMAIN = null;

    private Router $router;

    /**
     * {@inheritdoc}
     */
    public function provideStaticRoutes(): iterable
    {
        yield 'Best Case' => ['route' => '/abc0', 'result' => 'Rareloop'];

        yield 'Average Case' => ['route' => '/abc199', 'result' => 'Rareloop'];

        yield 'Worst Case' => ['route' => '/abc399', 'result' => 'Rareloop'];

        yield 'Invalid Method' => ['invalid' => self::INVALID_METHOD, 'route' => '/abc399', 'result' => 'Resource not found'];
    }

    /**
     * {@inheritdoc}
     */
    public function provideDynamicRoutes(): iterable
    {
        yield 'Best Case' => ['route' => '/abcbar/0', 'result' => 'Rareloop'];

        yield 'Average Case' => ['route' => '/abcbar/199', 'result' => 'Rareloop'];

        yield 'Worst Case' => ['route' => '/abcbar/399', 'result' => 'Rareloop'];

        yield 'Invalid Method' => ['invalid' => self::INVALID_METHOD, 'route' => '/abcbar/399', 'result' => 'Resource not found'];
    }

    /**
     * {@inheritdoc}
     */
    public function provideOtherScenarios(): iterable
    {
        yield 'Non Existent' => ['invalid' => self::SINGLE_METHOD, 'route' => '/testing', 'result' => 'Resource not found'];
    }

    /**
     * {@inheritdoc}
     */
    public function createDispatcher(): void
    {
        $router = new Router();

        for ($i = 0; $i < 400; ++$i) {
            $router->map(self::ALL_METHODS, '/abc' . $i, fn () => 'Rareloop')->name('static_' . $i);
            $router->map(self::ALL_METHODS, '/abc{foo}/' . $i, fn () => 'Rareloop')->name('not_static_' . $i);
        }

        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    protected function runScenario(array $params): void
    {
        if (isset($params['invalid']) || \is_string($params['method'])) {
            $request = new ServerRequest([], [], $params['route'], $params['invalid'] ?? $params['method']);
            $result = (string) $this->router->match($request)->getBody();

            \assert($params['result'] === $result);
        } else {
            foreach ($params['method'] as $method) {
                $request = new ServerRequest([], [], $params['route'], $method);
                $result = (string) $this->router->match($request)->getBody();

                \assert($params['result'] === $result);
            }
        }
    }
}
