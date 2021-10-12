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
use Nette\Http\Request;
use Nette\Http\UrlScript;
use Nette\Routing\RouteList;
use Nette\Routing\Router;

/**
 * @Groups({"nette", "raw"})
 */
class NetteRouter extends AbstractRouter
{
    private Router $router;

    /**
     * {@inheritdoc}
     */
    public function provideStaticRoutes(): iterable
    {
        yield 'Best Case' => ['route' => '/abc0'];

        yield 'Average Case' => ['route' => '/abc199'];

        yield 'Worst Case' => ['route' => '/abc399'];

        yield 'Invalid Method' => ['invalid' => self::INVALID_METHOD, 'route' => '/abc399'];
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
        $router = new RouteList();

        for ($i = 0; $i < 400; ++$i) {
            $router->addRoute('/abc' . $i, ['method' => self::ALL_METHODS]);
            $router->addRoute('/abc{foo}/' . $i, ['method' => self::ALL_METHODS]);

            $router->addRoute('//' . self::DOMAIN . '/host/abc' . $i, ['method' => self::ALL_METHODS]);
            $router->addRoute('//' . self::DOMAIN . '/host/abc<foo>/' . $i, ['method' => self::ALL_METHODS]);
        }

        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    protected function runScenario(array $params): void
    {
        $path = new UrlScript((isset($params['domain']) ? '//' . $params['domain'] . '/host' : '') . $params['route']);
        $method = $params['invalid'] ?? $params['method'];
        $request  = new Request($path, null, null, null, null, $method);
        $result = $this->router->match($request);

        \assert(isset($params['result']) ? null === $result : \in_array($method, $result['method'], true));
    }
}
