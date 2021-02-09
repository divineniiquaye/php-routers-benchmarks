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
    protected SimpleRouter $router;

    /**
     * {@inheritdoc}
     */
    public function testStatic(): bool
    {
        $methods = $this->generator->getMethods();

        foreach ($methods as $method) {
            $path = ($this->strategy)($method);

            try {
                $request = $this->router::request();
                $request->setUrl((new \Pecee\Http\Url($path)));
                $request->setMethod(\strtolower($method));

                \ob_start();
                $this->router->start();
                \ob_get_clean();
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
        $methods = $this->generator->getMethods();

        foreach ($methods as $method) {
            $path = ($this->strategy)($method);

            try {
                $request = $this->router::request();
                $request->setUrl((new \Pecee\Http\Url($path . 'pecee_router')));
                $request->setMethod(\strtolower($method));

                \ob_start();
                $this->router->start();
                \ob_get_clean();
            } catch (NotFoundHttpException $e) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function buildRoutes(array $routes): void
    {
        $router = new SimpleRouter();

        foreach ($routes as $route) {
            foreach ($route['methods'] as $method) {
                $router->match([\strtolower($method)], $route['pattern'], fn () => '');
            }
        }

        $this->router = $router;
    }
}
