<?php

namespace Kyoushu\ProgressTracker\Formatter;

use Kyoushu\ProgressTracker\TrackerInterface;

interface FormatterInterface
{

    public function format(TrackerInterface $tracker): string;

}