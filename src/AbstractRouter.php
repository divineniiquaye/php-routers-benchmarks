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
    protected const PATH = '{world}';

    protected const HOST = '{extension}';

    /** @var array<string,mixed> */
    protected array $config;

    protected ?CaseInterface $strategy;

    protected RouteGenerator $generator;

    /**
     * @param CaseInterface|null $strategy
     * @param RouteGenerator $generator
     * @param array<string,mixed> $config
     */
    public function __construct(?CaseInterface $strategy, RouteGenerator $generator, array $config)
    {
        $this->config    = $config;
        $this->strategy  = $strategy;
        $this->generator = $generator;
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
     *
     * @return mixed
     */
    abstract protected function buildRoutes(array $routes);

    /**
     * Get the strategy for benchmarking
     *
     * @param bool $isStatic
     * @param bool $isHost
     *
     * @return mixed[] an array of [$router, $strategy]
     */
    protected function getStrategy(bool $isStatic = false, bool $isHost = false): array
    {
        $config = [
            'isolated' => $this->config['isolated'],
            'static'   => $isStatic,
        ];

        if ($isHost && isset($this->config['nbHosts'])) {
            $config['nbHosts'] = $this->config['nbHosts'];
        }

        list($ids, $routes) = $this->generator->generate($config);

        $router = $this->buildRoutes($routes);
        $this->strategy->add($ids);

        return [$router, $this->strategy];
    }
}
