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

use Symfony\Component\Routing\Loader\ClosureLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;

/**
 * @Groups({"symfony", "cached"})
 */
class SymfonyRouterCached extends SymfonyRouter
{
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

        $this->router = new Router(new ClosureLoader(), $resource, ['cache_dir' => __DIR__ . '/../caches/symfony']);
    }
}
