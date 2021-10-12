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
use Pecee\Http\Url;
use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use Pecee\SimpleRouter\SimpleRouter;

/**
 * @Groups({"pecee-router", "raw"})
 * @Skip
 */
class PeceeRouter extends AbstractRouter
{
    private SimpleRouter $router;

    /**
     * {@inheritdoc}
     */
    public function provideStaticRoutes(): iterable
    {
        yield 'Best Case' => ['route' => '/abc0', 'result' => 'Pecee'];

        yield 'Average Case' => ['route' => '/abc199', 'result' => 'Pecee'];

        yield 'Worst Case' => ['route' => '/abc399', 'result' => 'Pecee'];

        yield 'Invalid Method' => ['invalid' => self::INVALID_METHOD, 'route' => '/abc399', 'result' => 403];
    }

    /**
     * {@inheritdoc}
     */
    public function provideDynamicRoutes(): iterable
    {
        yield 'Best Case' => ['route' => '/abcbar/0', 'result' => 'Pecee'];

        yield 'Average Case' => ['route' => '/abcbar/199', 'result' => 'Pecee'];

        yield 'Worst Case' => ['route' => '/abcbar/399', 'result' => 'Pecee'];

        yield 'Invalid Method' => ['invalid' => self::INVALID_METHOD, 'route' => '/abcbar/399', 'result' => 403];
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
        $router = new SimpleRouter();

        for ($i = 0; $i < 400; ++$i) {
            $router::match(self::ALL_METHODS, '/abc' . $i, fn () => 'Pecee', ['as' => 'static_' . $i]);
            $router::match(self::ALL_METHODS, '/abc{foo}/' . $i, fn () => 'Pecee', ['as' => 'not_static_' . $i]);

            $router::group(['domain' => self::DOMAIN], function () use ($router, $i) {
                $router::match(self::ALL_METHODS, '/host/abc' . $i, fn () => 'Pecee', ['as' => 'static_host_' . $i]);
                $router::match(self::ALL_METHODS, '/host/abc{foo}/' . $i, fn () => 'Pecee', ['as' => 'not_static_host_' . $i]);
            });
        }

        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    protected function runScenario(array $params): void
    {
        $request = $this->router::request();
        $request->setUrl(new Url((isset($params['domain']) ? '//' . $params['domain'] . '/host' : '') . $params['route']));
        $request->setMethod($params['invalid'] ?? $params['method']);

        try {
            $result = $this->router::router()->start();
            \assert($params['result'] === $result);
        } catch (NotFoundHttpException $e) {
            \assert($params['result'] === $e->getCode());
        }
    }
}
