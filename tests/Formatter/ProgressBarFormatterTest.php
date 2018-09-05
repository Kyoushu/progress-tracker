<?php

namespace Tests\Kyoushu\ProgressTracker\Formatter;

use Kyoushu\ProgressTracker\Formatter\ProgressBarFormatter;
use PHPUnit\Framework\TestCase;
use Tests\Kyoushu\ProgressTracker\MockTracker;

class ProgressBarFormatterTest extends TestCase
{

    public function testFormat()
    {
        $tracker = new MockTracker();
        $tracker->setProgressPercentage(50);

        $formatter = new ProgressBarFormatter(20, 0);
        $output = $formatter->format($tracker);
        $this->assertEquals('███████░░░░░░░░ 50% ', $output);

        $formatter = new ProgressBarFormatter(30, 0);
        $output = $formatter->format($tracker);
        $this->assertEquals('████████████░░░░░░░░░░░░░ 50% ', $output);

        $formatter = new ProgressBarFormatter(20, 1);
        $output = $formatter->format($tracker);
        $this->assertEquals('██████░░░░░░░ 50.0% ', $output);

        $formatter = new ProgressBarFormatter(20, 2);
        $output = $formatter->format($tracker);
        $this->assertEquals('██████░░░░░░ 50.00% ', $output);

    }

    public function testFormatBar()
    {
        $tracker = new MockTracker();
        $formatter = new ProgressBarFormatter(20, 0);

        $tracker->setProgressPercentage(0);
        $bar = $formatter->formatBar($tracker);

        $this->assertEquals(15, mb_strlen($bar));
        $this->assertEquals('░░░░░░░░░░░░░░░', $bar);

        $tracker->setProgressPercentage(25);
        $bar = $formatter->formatBar($tracker);
        $this->assertEquals('███░░░░░░░░░░░░', $bar);

        $tracker->setProgressPercentage(50);
        $bar = $formatter->formatBar($tracker);
        $this->assertEquals('███████░░░░░░░░', $bar);

        $tracker->setProgressPercentage(75);
        $bar = $formatter->formatBar($tracker);
        $this->assertEquals('███████████░░░░', $bar);

        $tracker->setProgressPercentage(99.9999);
        $bar = $formatter->formatBar($tracker);
        $this->assertEquals('██████████████░', $bar);

        $tracker->setProgressPercentage(100);
        $bar = $formatter->formatBar($tracker);
        $this->assertEquals('███████████████', $bar);

        $formatter = new ProgressBarFormatter(20, 1);
        $bar = $formatter->formatBar($tracker);
        $this->assertEquals(13, mb_strlen($bar));

        $formatter = new ProgressBarFormatter(20, 2);
        $bar = $formatter->formatBar($tracker);
        $this->assertEquals(12, mb_strlen($bar));
    }

    public function testFormatLabel()
    {
        $tracker = new MockTracker();
        $formatter = new ProgressBarFormatter(20, 0);

        $tracker->setProgressPercentage(1);
        $label = $formatter->formatLabel($tracker);
        $this->assertEquals(5, mb_strlen($label));
        $this->assertEquals(' 1%  ', $label);

        $tracker->setProgressPercentage(10);
        $label = $formatter->formatLabel($tracker);
        $this->assertEquals(5, mb_strlen($label));
        $this->assertEquals(' 10% ', $label);

        $tracker->setProgressPercentage(100);
        $label = $formatter->formatLabel($tracker);
        $this->assertEquals(5, mb_strlen($label));
        $this->assertEquals(' 100%', $label);

        $formatter = new ProgressBarFormatter(20, 1);

        $tracker->setProgressPercentage(1);
        $label = $formatter->formatLabel($tracker);
        $this->assertEquals(7, mb_strlen($label));
        $this->assertEquals(' 1.0%  ', $label);

        $tracker->setProgressPercentage(10);
        $label = $formatter->formatLabel($tracker);
        $this->assertEquals(7, mb_strlen($label));
        $this->assertEquals(' 10.0% ', $label);

        $tracker->setProgressPercentage(100);
        $label = $formatter->formatLabel($tracker);
        $this->assertEquals(7, mb_strlen($label));
        $this->assertEquals(' 100.0%', $label);

        $formatter = new ProgressBarFormatter(20, 2);

        $tracker->setProgressPercentage(1);
        $label = $formatter->formatLabel($tracker);
        $this->assertEquals(8, mb_strlen($label));
        $this->assertEquals(' 1.00%  ', $label);

        $tracker->setProgressPercentage(10);
        $label = $formatter->formatLabel($tracker);
        $this->assertEquals(8, mb_strlen($label));
        $this->assertEquals(' 10.00% ', $label);

        $tracker->setProgressPercentage(100);
        $label = $formatter->formatLabel($tracker);
        $this->assertEquals(8, mb_strlen($label));
        $this->assertEquals(' 100.00%', $label);
    }

}