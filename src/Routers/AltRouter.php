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

use AltoRouter;
use App\BenchMark\AbstractRouter;

class AltRouter extends AbstractRouter
{
    protected const DOMAIN = null;

    private AltoRouter $router;

    /**
     * {@inheritdoc}
     */
    public function provideStaticRoutes(): iterable
    {
        yield 'Best Case' => ['route' => '/abc0', 'result' => 'Alto'];

        yield 'Average Case' => ['route' => '/abc199', 'result' => 'Alto'];

        yield 'Worst Case' => ['route' => '/abc399', 'result' => 'Alto'];

        yield 'Invalid Method' => ['invalid' => self::INVALID_METHOD, 'route' => '/abc399', 'result' => false];
    }

    /**
     * {@inheritdoc}
     */
    public function provideDynamicRoutes(): iterable
    {
        yield 'Best Case' => ['route' => '/abcbar/0', 'result' => 'Alto'];

        yield 'Average Case' => ['route' => '/abcbar/199', 'result' => 'Alto'];

        yield 'Worst Case' => ['route' => '/abcbar/399', 'result' => 'Alto'];

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
        $router = new AltoRouter();

        for ($i = 0; $i < 400; ++$i) {
            $router->map(\implode('|', self::ALL_METHODS), '/abc' . $i, 'Alto', 'static_' . $i);
            $router->map(\implode('|', self::ALL_METHODS), '/abc[a:foo]/' . $i, 'Alto', 'not_static_' . $i);
        }

        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    protected function runScenario(array $params): void
    {
        if (isset($params['invalid']) || \is_string($params['method'])) {
            $result = $this->router->match($params['route'], $params['invalid'] ?? $params['method']);
            \assert($params['result'] === (\is_array($result) ? $result['target'] : false));
        } else {
            foreach ($params['method'] as $method) {
                $result = $this->router->match($params['route'], $method);
                \assert($params['result'] === $result['target']);
            }
        }
    }
}
