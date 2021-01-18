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

namespace App\BenchMark\Reporter\Printer;

use App\BenchMark\Reporter\BenchMark;

class Text implements PrinterInterface
{
    protected BenchMark $benchmark;

    /**
     * {@inheritdoc}
     */
    public function bind(BenchMark $benchmark): void
    {
        $this->benchmark = $benchmark;
    }

    /**
     * {@inheritdoc}
     */
    public function chart(): string
    {
        $result    = '';
        $maxLength = $maxRate = 0;
        $ranking   = $this->benchmark->getMatrix()->ranking();

        foreach ($ranking as $task) {
            if ($task->failed()) {
                continue;
            }

            if ($task->rate() > $maxRate) {
                $maxRate = $task->rate();
            }

            if (\mb_strlen($task->name()) > $maxLength) {
                $maxLength = \mb_strlen($task->name());
            }
        }

        foreach ($ranking as $task) {
            $name = $task->name();
            $result .= $this->strPad($name, $maxLength, ' ', \STR_PAD_RIGHT);

            if ($task->failed()) {
                $ratio = 0;
                $result .= $this->strPad('x', 10);
            } else {
                $rate  = $task->rate();
                $ratio = ($rate / $maxRate);
                $result .= $this->strPad(\round($ratio * 100) . '%', 10);
            }
            $result .= ' | ';

            $width = 60;
            $chars = (int) ($width * $ratio);
            $result .= \str_repeat('█', $chars);
            $result .= \str_repeat(' ', $width - $chars);
            $result .= "  |\n";
        }

        return $result;
    }

    /**
     * Returns the report.
     *
     * @return string the report
     */
    public function table(): string
    {
        $ranking = $this->benchmark->getMatrix()->ranking();
        $matrix  = $this->benchmark->getMatrix()->matrix();

        if (!$ranking) {
            return '';
        }

        $columnLength = [];
        $maxLength    = 0;

        foreach ($ranking as $task) {
            $name = $task->name();

            if (\preg_match('~^([\w\s]+)~', $name, $matches)) {
                $columnLength[$name] = \mb_strlen(\trim($matches[1]));
            } else {
                $columnLength[$name] = \mb_strlen($name);
            }

            if (\mb_strlen($name) > $maxLength) {
                $maxLength = \mb_strlen($name);
            }
        }

        $result = '';
        $result .= $this->strPad('', $maxLength);
        $result .= $this->strPad('Rate', 10);
        $result .= $this->strPad('Mem', 8);

        foreach ($ranking as $task) {
            $name = $task->name();

            if (\preg_match('~^([\w\s]+)~', $name, $matches)) {
                $result .= $this->strPad(\trim($matches[1]), $columnLength[$name] + 2);
            } else {
                $result .= $this->strPad($name, $columnLength[$name] + 2);
            }
        }
        $result .= "\n";

        foreach ($ranking as $task1) {
            $name1 = $task1->name();
            $result .= $this->strPad($name1, $maxLength, ' ', \STR_PAD_RIGHT);
            $task1 = $this->_benchmark->task($name1);

            $result .= $this->strPad($this->readableSize($task1->rate()) . '/s', 10);
            $result .= $this->strPad($this->readableSize($task1->memory(), 0, 1024) . 'B', 8);

            foreach ($ranking as $task2) {
                $name2 = $task2->name();

                if ($task1->failed() || $task2->failed()) {
                    $result .= $this->strPad('x', $columnLength[$name2] + 2);
                } else {
                    $percent = $matrix[$name1][$name2] !== 100 ? $matrix[$name1][$name2] : '--';
                    $result .= $this->strPad($percent . '%', $columnLength[$name2] + 2);
                }
            }
            $result .= "\n";
        }

        return $result;
    }

    /**
     * Humanizes values using an appropriate unit.
     *
     * @return int    $value     the value
     * @return int    $precision the required precision
     * @return int    $base      the unit base
     * @return string the Humanized string value
     */
    public function readableSize($value, $precision = 0, $base = 1000): string
    {
        $i = 0;

        if (!$value) {
            return '0';
        }
        $isNeg = false;

        if ($value < 0) {
            $isNeg = true;
            $value = -$value;
        }

        if ($value >= 1) {
            $units = ['', 'K', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y'];

            while (($value / $base) >= 1) {
                $value = $value / $base;
                $i++;
            }

            $unit = $units[$i] ?? '?';
        } else {
            $units = ['', 'm', 'µ', 'n', 'p', 'f', 'a', 'z'];

            while (($value * $base) <= $base) {
                $value = $value * $base;
                $i++;
            }

            $unit = $units[$i] ?? '?';
        }

        return \round($isNeg ? -$value : $value, $precision) . $unit;
    }

    /**
     * Pad a string to a certain length with another string.
     *
     * @param string $input  the input string
     * @param string $length the padding length
     * @param string $string the padding string
     * @param string $type   the type of padding
     *
     * @return string the padded string
     */
    public function strPad($input, $length, $string = ' ', $type = \STR_PAD_LEFT)
    {
        return \str_pad($input, $length + \strlen($input) - \mb_strlen($input), $string, $type);
    }
}
