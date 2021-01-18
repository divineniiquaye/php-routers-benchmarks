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

namespace App\BenchMark\Strategy;

interface CaseInterface
{
    /**
     * Return the result of a case strategy
     *
     * @param string $method
     * @param string $host
     *
     * @return mixed
     */
    public function __invoke(string $method, string $host = '*');

    /**
     * Receives an array of routes after generation.
     *
     * @param array<string,array<string,array<int,string[]>>> $ids
     */
    public function add(array $ids): void;
}
