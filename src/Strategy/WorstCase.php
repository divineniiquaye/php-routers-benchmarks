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

class WorstCase extends AverageCase
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(string $method, string $host = '*')
    {
        return \end($this->ids[$host][$method]);
    }
}
