<?php

namespace Kyoushu\ProgressTracker;

abstract class AbstractTracker implements TrackerInterface
{

    /**
     * @var \DateTimeInterface|null
     */
    protected $dateTimeStarted;

    /**
     * @var \DateTimeInterface|null
     */
    protected $dateTimeEnded;

    /**
     * @param \DateTimeInterface|null $now
     * @return \DateTimeInterface|null
     * @throws \Exception
     */
    public function getDateTimeEta(\DateTimeInterface $now = null): ?\DateTimeInterface
    {
        $started = $this->getDateTimeStarted();
        $ended = $this->getDateTimeEnded();
        if($ended !== null || $started === null) return null;

        $secondsTotal = $this->getSecondsTotal($now);
        $interval = new \DateInterval(sprintf('PT%sS', $secondsTotal));
        $eta = new \DateTime($started->format('c'));
        $eta->add($interval);

        return $eta;
    }

    /**
     * @param \DateTimeInterface|null $now
     * @return int|null
     * @throws \Exception
     */
    public function getSecondsTotal(\DateTimeInterface $now = null): ?int
    {
        $progress = $this->getProgressPercentage();
        if($progress === 0) return null;

        $secondsElapsed = $this->getSecondsElapsed($now);
        if($secondsElapsed === null) return null;
        return round(($secondsElapsed / $progress) * 100);
    }

    /**
     * @param \DateTimeInterface|null $now
     * @return int|null
     * @throws \Exception
     */
    public function getSecondsElapsed(\DateTimeInterface $now = null): ?int
    {
        if($now === null) $now = new \DateTimeImmutable('now');
        $started = $this->getDateTimeStarted();
        if($started === null) return null;
        return (int)$now->format('U') - (int)$started->format('U');
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateTimeStarted(): ?\DateTimeInterface
    {
        return $this->dateTimeStarted;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateTimeEnded(): ?\DateTimeInterface
    {
        return $this->dateTimeEnded;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function start(): TrackerInterface
    {
        $this->dateTimeStarted = new \DateTimeImmutable('now');
        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function end(): TrackerInterface
    {
        $this->dateTimeEnded = new \DateTimeImmutable('now');
        return $this;
    }


}