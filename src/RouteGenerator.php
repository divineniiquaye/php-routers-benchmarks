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

use Fig\Http\Message\RequestMethodInterface;
use Lead\Text\Text;

class RouteGenerator
{
    /** The number of routes. */
    protected int $nbRoutes;

    /** The number of hosts. */
    protected int $nbHosts;

    /** Where to use all methods or not. */
    protected bool $isolated;

    /** The scheme constraint. */
    protected ?string $scheme = null;

    /** The host structure template. */
    protected ?string $host = null;

    /** @var string[] The built hosts. */
    protected array $hosts = [];

    /** The path token structure template. */
    protected ?string $template = null;

    /** @var array<string,string> The constraints. */
    protected array $constraints = [];

    /** @var string[] Archaic HTTP method distribution. */
    protected array $methods = [
        RequestMethodInterface::METHOD_GET,
        RequestMethodInterface::METHOD_POST,
        RequestMethodInterface::METHOD_PUT,
        RequestMethodInterface::METHOD_PATCH,
        RequestMethodInterface::METHOD_DELETE,
    ];

    public function __construct(bool $isolated, int $routes = 1, int $hosts = 1)
    {
        $this->isolated = $isolated;
        $this->nbRoutes = $routes;
        $this->nbHosts  = $hosts;
    }

    /**
     * Get all methods
     *
     * @return string[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * Get all generated hosts
     *
     * @return array
     */
    public function getHosts(): array
    {
        return $this->hosts;
    }

    public function setHost(string $host, string $scheme = 'http'): void
    {
        $this->scheme = $scheme;
        $this->host   = $host;
    }

    public function setTemplate(string $template, array $constraints = []): void
    {
        $this->template    = $template;
        $this->constraints = $constraints;
    }

    /**
     * Generates a bunch of routes.
     *
     * @param bool $isStatic
     *
     * @return mixed the generated routes array
     */
    public function generate(bool $isStatic = false): array
    {
        $scheme      = $this->scheme;
        $hosts       = ['*' => '*'];
        $constraints = $this->constraints;

        if (!$isStatic && !$this->template) {
            throw new \RuntimeException('Missing path template.');
        }

        if (null !== $this->host) {
            $pattern = $this->host;

            for ($i = 1; $i <= $this->nbHosts; $i++) {
                $host = Text::insert($pattern, $constraints, ['before' => '{%', 'after' => '%}']);

                $hosts["subdomain{$i}.domain."] = \sprintf( 'subdomain%s.domain.%s', $i, $host);
            }
        }

        $ids = [];
        $id  = 1;

        foreach ($hosts as $domain => $host) {
            $this->hosts[] = $domain;
            $ids[$domain]  = [
                RequestMethodInterface::METHOD_GET    => [],
                RequestMethodInterface::METHOD_POST   => [],
                RequestMethodInterface::METHOD_PUT    => [],
                RequestMethodInterface::METHOD_PATCH  => [],
                RequestMethodInterface::METHOD_DELETE => [],
            ];

            for ($i = 0; $i < $this->nbRoutes; $i++) {
                $path    = \sprintf('/controller%s/action%1$s/', $id);
                $pattern = $path . Text::insert($this->template, $constraints, ['before' => '{%', 'after' => '%}']);
                $methods = $this->isolated ? [$this->methods[$i % 5]] : $this->methods;
                $name    = '_' . $id;

                if ($isStatic) {
                    $pattern = $this->template ?? $path;
                }

                $routes[] = \compact('name', 'scheme', 'host', 'pattern', 'methods', 'constraints');

                foreach ($methods as $method) {
                    $ids[$domain][$method][] = $path;
                }

                $id++;
            }
        }

        return [$ids, $routes];
    }
}
