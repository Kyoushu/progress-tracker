<?php

namespace Kyoushu\ProgressTracker;

interface TrackerInterface
{

    public function getProgressPercentage(): float;

    public function getDateTimeEta(\DateTimeInterface $now = null): ?\DateTimeInterface;

    public function getDateTimeStarted(): ?\DateTimeInterface;

    public function getDateTimeEnded(): ?\DateTimeInterface;

    public function start(): self;

    public function end(): self;

}