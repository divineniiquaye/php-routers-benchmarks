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
use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use Pecee\SimpleRouter\SimpleRouter;

class PeceeRouter extends AbstractRouter
{
    /**
     * {@inheritdoc}
     */
    public function testStatic(): bool
    {
        /** @var SimpleRouter $router */
        list($router, $strategy) = $this->getStrategy(true);

        foreach ($this->generator->getMethods() as $method) {
            [, $path] = $strategy($method);

            try {
                $request = $router::request();
                $request->setUrl((new \Pecee\Http\Url($path)));
                $request->setMethod(\strtolower($method));

                $router->start();
            } catch (NotFoundHttpException $e) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function testPath(): bool
    {
        $this->generator->setTemplate(self::PATH);

        /** @var SimpleRouter $router */
        list($router, $strategy) = $this->getStrategy();

        foreach ($this->generator->getMethods() as $method) {
            [, $path] = $strategy($method);

            try {
                $request = $router::request();
                $request->setUrl((new \Pecee\Http\Url($path . 'pecee_router')));
                $request->setMethod(\strtolower($method));

                $router->start();
            } catch (NotFoundHttpException $e) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function buildRoutes(array $routes): SimpleRouter
    {
        $router = new SimpleRouter();

        foreach ($routes as $route) {
            foreach ($route['methods'] as $method) {
                $router->match([\strtolower($method)], $route['pattern'], fn () => '');
            }
        }

        return $router;
    }
}
