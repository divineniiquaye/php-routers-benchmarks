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
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\UriFactory;
use Psr\Http\Message\ResponseFactoryInterface;
use Spiral\Core\Container;
use Spiral\Router\Exception\RouteNotFoundException;
use Spiral\Router\Route;
use Spiral\Router\Router;
use Spiral\Router\RouterInterface;
use Spiral\Router\UriHandler;

/**
 * @Groups({"spiral", "raw"})
 */
class SpiralRouter extends AbstractRouter
{
    private RouterInterface $router;

    /**
     * {@inheritdoc}
     */
    public function provideStaticRoutes(): iterable
    {
        yield 'Best Case' => ['route' => '/abc0', 'result' => 'Spiral'];

        yield 'Average Case' => ['route' => '/abc199', 'result' => 'Spiral'];

        yield 'Worst Case' => ['route' => '/abc399', 'result' => 'Spiral'];

        yield 'Invalid Method' => ['invalid' => self::INVALID_METHOD, 'route' => '/abc399'];
    }

    /**
     * {@inheritdoc}
     */
    public function provideDynamicRoutes(): iterable
    {
        yield 'Best Case' => ['route' => '/abcbar/0', 'result' => 'Spiral'];

        yield 'Average Case' => ['route' => '/abcbar/199', 'result' => 'Spiral'];

        yield 'Worst Case' => ['route' => '/abcbar/399', 'result' => 'Spiral'];

        yield 'Invalid Method' => ['invalid' => self::INVALID_METHOD, 'route' => '/abcbar/399'];
    }

    /**
     * {@inheritdoc}
     */
    public function provideOtherScenarios(): iterable
    {
        yield 'Non Existent' => ['invalid' => self::SINGLE_METHOD, 'route' => '/testing'];
    }

    /**
     * {@inheritdoc}
     */
    public function createDispatcher(): void
    {
        $container = new Container();
        $container->bind(ResponseFactoryInterface::class, new ResponseFactory());

        $router = new Router('/', new UriHandler(new UriFactory()), $container);

        for ($i = 0; $i < 400; ++$i) {
            $router->setRoute('static_' . $i, (new Route('/abc' . $i, fn () => 'Spiral'))->withVerbs(...self::ALL_METHODS));
            $router->setRoute('not_static_' . $i, (new Route('/abc<foo>/' . $i, fn () => 'Spiral'))->withVerbs(...self::ALL_METHODS));

            $router->setRoute('static_host_' . $i, (new Route('//' . self::DOMAIN . '/host/abc' . $i, fn () => 'Spiral'))->withVerbs(...self::ALL_METHODS));
            $router->setRoute('not_static_host_' . $i, (new Route('//' . self::DOMAIN . '/host/abc<foo>/' . $i, fn () => 'Spiral'))->withVerbs(...self::ALL_METHODS));
        }

        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    protected function runScenario(array $params): void
    {
        $uri = (isset($params['domain']) ? '//' . $params['domain'] . '/host' : '') . $params['route'];
        $request = new ServerRequest([], [], $uri, $params['invalid'] ?? $params['method']);

        try {
            $result = (string) $this->router->handle($request)->getBody();
            \assert($params['result'] === $result);
        } catch (RouteNotFoundException $e) {
            \assert(!isset($params['result']));
        }
    }
}
