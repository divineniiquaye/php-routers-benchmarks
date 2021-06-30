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
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sunrise\Http\Message\ResponseFactory;
use Sunrise\Http\Router\Exception\MethodNotAllowedException;
use Sunrise\Http\Router\Exception\RouteNotFoundException;
use Sunrise\Http\Router\RequestHandler\CallableRequestHandler;
use Sunrise\Http\Router\RouteCollector;
use Sunrise\Http\Router\Router;
use Sunrise\Http\ServerRequest\ServerRequestFactory;
use Sunrise\Uri\Uri;

class SunriseRouter extends AbstractRouter
{
    public const HOST = '';

    private Router $router;

    /**
     * {@inheritdoc}
     */
    public function provideStaticRoutes(): iterable
    {
        yield 'Best Case' => ['route' => '/abc0', 'result' => ['status' => 'ok', 'message' => 'Hello World']];

        yield 'Average Case' => ['route' => '/abc199', 'result' => ['status' => 'ok', 'message' => 'Hello World']];

        yield 'Worst Case' => ['route' => '/abc399', 'result' => ['status' => 'ok', 'message' => 'Hello World']];

        yield 'Invalid Method' => ['invalid' => self::INVALID_METHOD, 'route' => '/abc399'];
    }

    /**
     * {@inheritdoc}
     */
    public function provideDynamicRoutes(): iterable
    {
        yield 'Best Case' => ['route' => '/abcbar/0', 'result' => ['status' => 'ok', 'message' => 'Hello World']];

        yield 'Average Case' => ['route' => '/abcbar/199', 'result' => ['status' => 'ok', 'message' => 'Hello World']];

        yield 'Worst Case' => ['route' => '/abcbar/399', 'result' => ['status' => 'ok', 'message' => 'Hello World']];

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
        $collection = new RouteCollector();
        $handler = new CallableRequestHandler(
            function (ServerRequestInterface $request): ResponseInterface {
                return (new ResponseFactory())->createJsonResponse(200, [
                    'status' => 'ok',
                    'message' => 'Hello World',
                ]);
            }
        );

        for ($i = 0; $i < 400; ++$i) {
            $collection->route('static_' . $i, '/abc' . $i, self::ALL_METHODS, $handler);
            $collection->route('not_static_' . $i, '/abc{foo}/' . $i, self::ALL_METHODS, $handler);

            $collection->route('static_host_' . $i, '/host/abc' . $i, self::ALL_METHODS, $handler)->setHost(self::DOMAIN);
            $collection->route('not_static_host_' . $i, '/host/abc{foo}/' . $i, self::ALL_METHODS, $handler)->setHost(self::DOMAIN);
        }

        $this->router = new Router();
        $this->router->addRoute(...$collection->getCollection()->all());
    }

    /**
     * {@inheritdoc}
     */
    protected function runScenario(array $params): void
    {
        $uri = new Uri((isset($params['domain']) ? '//' . $params['domain'] . '/host' : '') . $params['route']);

        if (isset($params['invalid']) || \is_string($params['method'])) {
            $request = (new ServerRequestFactory())->createServerRequest($params['invalid'] ?? $params['method'], $uri);

            try {
                $result = (string) $this->router->handle($request)->getBody();
                \assert($params['result'] === $result);
            } catch (MethodNotAllowedException | RouteNotFoundException $e) {
                \assert(!isset($params['result']));
            }
        } else {
            foreach ($params['method'] as $method) {
                $request = (new ServerRequestFactory())->createServerRequest($method, $uri);
                $result = (string) $this->router->handle($request)->getBody();

                \assert($params['result'] === $result);
            }
        }
    }
}
