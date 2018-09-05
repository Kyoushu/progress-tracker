<?php

namespace Tests\Kyoushu\ProgressTracker;

use Kyoushu\ProgressTracker\AbstractTracker;

class MockTracker extends AbstractTracker
{

    protected $progressPercentage = 0.00;

    public function setDateTimeStarted(\DateTimeInterface $dateTimeStarted): self
    {
        $this->dateTimeStarted = $dateTimeStarted;
        return $this;
    }

    public function setDateTimeEnded(\DateTimeInterface $dateTimeEnded): self
    {
        $this->dateTimeEnded = $dateTimeEnded;
        return $this;
    }

    public function getProgressPercentage(): float
    {
        return $this->progressPercentage;
    }

    public function setProgressPercentage(float $progressPercentage): self
    {
        $this->progressPercentage = $progressPercentage;
        return $this;
    }

}