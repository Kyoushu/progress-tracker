<?php

namespace Kyoushu\ProgressTracker\Formatter;

use Kyoushu\ProgressTracker\TrackerInterface;
use Kyoushu\ProgressTracker\Util\StringUtil;

class ProgressBarFormatter implements FormatterInterface
{

    const CHAR_BAR_FILL = '█';
    const CHAR_BAR_BACKGROUND = '░';

    const DEFAULT_DECIMAL_PLACES = 1;

    /**
     * @var int
     */
    protected $cols;

    /**
     * @var int
     */
    protected $decimalPlaces;

    protected function getLabelCols()
    {
        return (
            5 + // Leading space, "%", and up to 3 numbers
            ($this->decimalPlaces > 0 ? $this->decimalPlaces + 1 : 0) // "." and decimal places
        );
    }

    public function __construct(int $cols, int $decimalPlaces = null)
    {
        $this->cols = $cols;
        $this->decimalPlaces = $decimalPlaces ?? self::DEFAULT_DECIMAL_PLACES;
    }

    public function formatLabel(TrackerInterface $tracker): string
    {
        $cols = $this->getLabelCols();
        $progressPercentage = $tracker->getProgressPercentage();
        $label = sprintf(' %s%%', number_format($progressPercentage, $this->decimalPlaces));
        $label = mb_substr($label, 0, $cols);
        $label = StringUtil::mb_str_pad($label, $cols, ' ', STR_PAD_RIGHT);
        return $label;
    }

    public function formatBar(TrackerInterface $tracker): string
    {
        $barWidth = $this->cols - $this->getLabelCols();

        $progressPercentage = $tracker->getProgressPercentage();
        $progressCols = floor($barWidth * ($progressPercentage / 100));

        $bar = str_repeat(self::CHAR_BAR_FILL, $progressCols);
        $bar = StringUtil::mb_str_pad($bar, $barWidth, self::CHAR_BAR_BACKGROUND, STR_PAD_RIGHT);

        return $bar;
    }

    public function format(TrackerInterface $tracker): string
    {
        return $this->formatBar($tracker) . $this->formatLabel($tracker);
    }

}