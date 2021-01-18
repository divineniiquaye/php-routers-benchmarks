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

namespace App\BenchMark\Reporter;

class Task
{
    protected string $name = '';

    protected int $repeat = 1;

    /** @var float|int Duration time (in seconds). */
    protected $duration = 0;

    /** @var float|int The average taken time per task (in seconds). */
    protected $average = 0;

    /** @var float|int The time rate. */
    protected $rate = 0;

    /** The memory usage (in bytes) */
    protected int $memory = 0;

    /** @var float Start time (in seconds). */
    protected $startTime = 0;

    /** @var int Start up memory usage (in bytes) */
    protected $startMemory = 0;

    /** @var bool Indicates if the task failed */
    protected $failed = false;

    /**
     * Starts the timer
     */
    public function start(): void
    {
        $this->startTime   = \microtime(true);
        $this->startMemory = \memory_get_usage(true);
    }

    /**
     * Stops the timer.
     */
    public function end(): void
    {
        $this->duration = \microtime(true) - $this->startTime;
        $this->average  = $this->duration / $this->repeat;
        $this->rate     = $this->repeat / $this->duration;
        $this->memory   = \memory_get_usage(true) - $this->startMemory;
    }

    /**
     * Gets/sets the name.
     *
     * @param null|string the task name to set or none the get the current one
     *
     * @return self|string name or `$this` on set
     */
    public function name(?string $name = null)
    {
        if (null !== $name) {
            $this->name = $name;

            return $this;
        }

        return $this->name;
    }

    /**
     * Gets/sets the repeat number.
     *
     * @param int $repeat the repeat value to set or none the get the current one
     *
     * @return int|self the repeat value or `$this` on set
     */
    public function repeat(?int $repeat = null)
    {
        if (null !== $repeat) {
            $this->clear();
            $this->repeat = $repeat;

            return $this;
        }

        return $this->repeat;
    }

    /**
     * Returns the average taken time.
     *
     * @return float|int the time taken per task, in average (in seconds)
     */
    public function average()
    {
        return $this->average;
    }

    /**
     * Returns the time rate.
     *
     * @return float|int the time rate
     */
    public function rate()
    {
        return $this->rate;
    }

    /**
     * Returns the whole taken time.
     *
     * @return float|int the time taken (in seconds)
     */
    public function duration()
    {
        return $this->duration;
    }

    /**
     * Returns the memory usage.
     *
     * @return int the memory usage (in bytes)
     */
    public function memory(): int
    {
        return $this->memory;
    }

    /**
     * Indicates whether the task failed or not.
     *
     * @var null|bool the failing value
     *
     * @return bool|self
     */
    public function failed(?bool $fail = null)
    {
        if (\is_bool($fail)) {
            $this->failed = $fail;

            return $this;
        }

        return $this->failed;
    }

    /**
     * Clears the stats.
     */
    public function clear(): void
    {
        $this->repeat    = 1;
        $this->startTime = 0;
        $this->duration  = 0;
        $this->average   = 0;
        $this->rate      = 0;
        $this->startMem  = 0;
        $this->memory    = 0;
    }
}
