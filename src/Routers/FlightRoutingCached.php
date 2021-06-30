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

use Flight\Routing\RouteCollection;
use Flight\Routing\RouteMatcher;

class FlightRoutingCached extends FlightRouting
{
    /**
     * {@inheritdoc}
     */
    public function createDispatcher(): void
    {
        $collection = new RouteCollection(null, false, __DIR__ . '/../caches/flight-routes.php');

        if (!$collection->isCached()) {
            for ($i = 0; $i < 400; ++$i) {
                $collection->addRoute('/abc' . $i, self::ALL_METHODS)->bind('static_' . $i);
                $collection->addRoute('/abc{foo}_{bar}-{baz}/' . $i, self::ALL_METHODS)->bind('not_static_' . $i);

                $collection->addRoute('//' . self::DOMAIN . '/host/abc' . $i, self::ALL_METHODS)->bind('static_host_' . $i);
                $collection->addRoute('//' . self::DOMAIN . '/host/abc{foo}_{bar}-{path}/' . $i, self::ALL_METHODS)->bind('not_static_host_' . $i);
            }
        }

        $this->router = new RouteMatcher($collection);
    }
}
