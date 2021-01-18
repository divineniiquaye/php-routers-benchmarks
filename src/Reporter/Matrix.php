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

class Matrix
{
    /** @var array<string,Task> The task collection. */
    protected array $tasks = [];

    /** The matrix result. */
    protected array $matrix = [];

    /** @var Task[] The ordered task name (faster first). */
    protected array $ranking = [];

    /**
     * @param Task[] $tasks a collection of tasks
     */
    public function __construct(array $tasks = [])
    {
        foreach ($tasks as $task) {
            $this->tasks[$task->name()] = $task;
        }
    }

    /**
     * Returns the matrix result.
     *
     * @return array
     */
    public function matrix()
    {
        return $this->matrix;
    }

    /**
     * Returns the tasks ranking.
     *
     * @return array
     */
    public function ranking()
    {
        return $this->ranking;
    }

    /**
     * Builds the matrix result.
     *
     * @return self
     */
    public function process(): self
    {
        $orderedTasks = $this->tasks;

        \usort($orderedTasks, function (Task $a, Task $b) {
            return $a->duration() > $b->duration() ? 1 : -1;
        });

        $this->ranking = $orderedTasks;
        $matrix        = [];

        foreach ($this->ranking as $task1) {
            $name1          = $task1->name();
            $matrix[$name1] = [];

            foreach ($this->ranking as $task2) {
                $name2                  = $task2->name();
                $percent                = (int) (\round($task1->duration() / $task2->duration() * 100));
                $matrix[$name1][$name2] = $percent;
            }
        }
        $this->matrix = $matrix;

        return $this;
    }
}
