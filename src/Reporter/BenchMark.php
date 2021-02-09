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

use App\BenchMark\Reporter\Printer\PrinterInterface;

class BenchMark
{
    /** @var int The repetition number. */
    protected int $repeat = 1;

    /** @var array<string,Task> The task collection. */
    protected array $tasks = [];

    /** The visual reporter instance. */
    protected PrinterInterface $printer;

    public function __construct(?PrinterInterface $printer = null)
    {
        $this->printer = $printer ?? new Printer\Text();
        $this->printer->bind($this);
    }

    /**
     * Returns the reporter
     *
     * @return string
     */
    public function report(): string
    {
        return $this->printer->chart();
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
            $this->repeat = $repeat;

            return $this;
        }

        return $this->repeat;
    }

    /**
     * Wraps a callable with start() and end() calls
     *
     * Additional arguments passed to this method will be passed to
     * the callable.
     *
     * @param callable $callable
     * @param  mixed    ...
     *
     * @return mixed
     */
    public function run(string $taskName, callable $callback)
    {
        $args = array_slice(\func_get_args(), 2);

        $task = $this->start($taskName, $this->repeat());

        for ($i = 0; $i < $task->repeat(); $i++) {
            $handle = ($callback)(...$args);

            if ($handle === false) {
                $task->failed(true);

                break;
            }
        }
        $this->end($taskName);

        return $task;
    }

    /**
     * Starts the timer for a task
     *
     * @param string   $taskName the taskname to start
     * @param intnull| $repeat   the number of times the task will be executed
     *
     * @return Task the started task
     */
    public function start(string $taskName, ?int $repeat = null)
    {
        $task = new Task();
        $task->name($taskName);

        if (null !== $repeat) {
            $task->repeat($repeat);
        }

        if (isset($this->tasks[$taskName])) {
            throw new \RuntimeException("Task {$taskName} is already defined.");
        }

        $this->tasks[$taskName] = $task;
        $task->start();

        return $task;
    }

    /**
     * Ends the timer for a task
     *
     * @param string $taskName the taskname to stop the timer for
     *
     * @return Task the stopped task
     */
    public function end(string $taskName): Task
    {
        if (!isset($this->tasks[$taskName])) {
            throw new \RuntimeException("Undefined task name: `'{$taskName}`.");
        }

        $task = $this->tasks[$taskName];
        $task->end();

        return $task;
    }

    /**
     * Returns a specific task.
     *
     * @param string $name the task name
     *
     * @return null|Task
     */
    public function task(string $name): ?Task
    {
        return $this->tasks[$name] ?? null;
    }

    /**
     * Returns all created tasks.
     *
     * @return Task[]
     */
    public function tasks()
    {
        return \array_values($this->tasks);
    }

    /**
     * Returns the total duration.
     *
     * @return float|int the total duration (in microseconds)
     */
    public function duration()
    {
        $duration = 0;

        foreach ($this->tasks as $task) {
            $duration += $task->duration();
        }

        return $duration;
    }

    /**
     * Returns the processed matrix result report.
     *
     * @return Matrix
     */
    public function getMatrix(): Matrix
    {
        $matrix = new Matrix($this->tasks());
        $matrix->process();

        return $matrix;
    }

    /**
     * Returns the system info.
     *
     * @return string the system info
     */
    public static function systemInfo(): string
    {
        $result = '';

        $result .= 'PHP Version: ' . \PHP_MAJOR_VERSION . '.' . \PHP_MINOR_VERSION . '.' . \PHP_RELEASE_VERSION . ' [' . \php_uname() . ']';

        if (\extension_loaded('xdebug')) {
            $result .= " - With XDebug Extension.\n";
        }

        return $result;
    }

    /**
     * Titleizes a string
     *
     * @param string $title the string to titleize
     * @param string $pad
     */
    public static function title(string $title, string $pad = '='): string
    {
        $rest = (78 - \mb_strlen($title)) / 2;

        $result = "\n\n";
        $result .= \str_repeat($pad, (int) $rest);
        $result .= ' ' . $title . ' ';
        $result .= \str_repeat($pad, (int) $rest);
        $result .= "\n\n";

        return $result;
    }
}
