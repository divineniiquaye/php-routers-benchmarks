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

/**
 * An abstraction for benchmarking routers.
 *
 * @Warmup(2)
 * @Revs(100)
 * @Iterations(5)
 *
 * @author Divine Niiquaye Ibok <divineibok@gmail.com>
 */
abstract class AbstractRouter
{
    protected const ALL_METHODS = ['GET', 'POST', 'PATCH', 'PUT', 'DELETE'];

    protected const SINGLE_METHOD = 'GET';

    protected const INVALID_METHOD = 'TRACE';

    protected const DOMAIN = 'benchmark.com';

    abstract public function createDispatcher(): void;

    /** @param array<string,mixed> $params */
    abstract protected function runScenario(array $params): void;

    /** @return \Generator<string,array<string,mixed>> */
    abstract public function provideStaticRoutes(): iterable;

    /** @return \Generator<string,array<string,mixed>> */
    abstract public function provideDynamicRoutes(): iterable;

    /** @return \Generator<string,array<string,mixed>> */
    abstract public function provideOtherScenarios(): iterable;

    /** @return \Generator<string,array<string,mixed>> */
    public function provideDispatcher(): iterable
    {
        $matchMethod = static::ALL_METHODS[\array_rand(static::ALL_METHODS)] ?? static::SINGLE_METHOD;

        yield $matchMethod => ['method' => $matchMethod];

        if (null !== static::DOMAIN) {
            yield $matchMethod . ',Host' => ['method' => $matchMethod, 'domain' => static::DOMAIN];
        }
    }

    /**
     * @BeforeMethods("createDispatcher")
     * @ParamProviders({"provideDispatcher", "provideStaticRoutes"})
     *
     * @param array<string,mixed> $params
     */
    public function benchStaticRoutes(array $params): void
    {
        $this->runScenario($params);
    }

    /**
     * @BeforeMethods("createDispatcher")
     * @ParamProviders({"provideDispatcher", "provideDynamicRoutes"})
     *
     * @param array<string,mixed> $params
     */
    public function benchDynamicRoutes(array $params): void
    {
        $this->runScenario($params);
    }

    /**
     * @BeforeMethods("createDispatcher")
     * @ParamProviders({"provideDispatcher", "provideOtherScenarios"})
     *
     * @param array<string,mixed> $params
     */
    public function benchOtherRoutes(array $params): void
    {
        $this->runScenario($params);
    }

    /**
     * @ParamProviders({"provideDispatcher", "provideStaticRoutes"})
     *
     * @param array<string,mixed> $params
     */
    public function benchRealStaticCase(array $params): void
    {
        $this->createDispatcher();
        $this->runScenario($params);
    }

    /**
     * @ParamProviders({"provideDispatcher", "provideStaticRoutes"})
     *
     * @param array<string,mixed> $params
     */
    public function benchRealDynamicCase(array $params): void
    {
        $this->createDispatcher();
        $this->runScenario($params);
    }
}
