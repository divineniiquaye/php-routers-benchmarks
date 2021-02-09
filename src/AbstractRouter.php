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

namespace App\BenchMark;

use App\BenchMark\Strategy\CaseInterface;

/**
 * An abstraction for benchmarking routers.
 *
 * @method bool testSubDomain()
 *
 * @author Divine Niiquaye Ibok <divineibok@gmail.com>
 */
abstract class AbstractRouter
{
    public const PATH = '{world}';

    public const HOST = '{extension}';

    protected string $type;

    protected bool $cache;

    protected CaseInterface $strategy;

    protected RouteGenerator $generator;

    /**
     * @param null|CaseInterface $strategy
     * @param RouteGenerator     $generator
     * @param string             $type
     * @param bool               $cache
     */
    public function __construct(CaseInterface $strategy, RouteGenerator $generator, string $type, bool $cache)
    {
        $this->type      = $type;
        $this->cache     = $cache;
        $this->strategy  = $strategy;
        $this->generator = $generator;
    }

    /**
     * Test Router against caching support
     *
     * @return bool
     */
    public static function isCacheable(): bool
    {
        return false;
    }

    /**
     * Test Dynamic path eg: /{var}
     *
     * @return bool
     */
    abstract public function testPath(): bool;

    /**
     * Test static path eg: /hello
     *
     * @return bool
     */
    abstract public function testStatic(): bool;

    /**
     * Build routes to be used in testPath, testStatic and testSubDomain
     *
     * @param array $routes
     */
    abstract public function buildRoutes(array $routes): void;

    /**
     * Get the cache directory or file is router supports caching.
     *
     * @param string $name
     * @param string $file
     *
     * @return null|string
     */
    protected function getCache(string $name, string $file = null): ?string
    {
        if ($this->isCacheable() && $this->cache) {
            static $subName;

            switch ($this->type) {
                case 'SubDomain':
                    $subName = '/hosts';

                    break;

                case 'Path':
                    $subName = '/paths';

                    break;

                case 'Static':
                    $subName = '/static';

                    break;
            }

            return __DIR__ . '/caches/' . $name . $subName . $file;
        }

        return null;
    }
}
