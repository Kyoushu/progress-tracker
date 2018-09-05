<?php

namespace Tests\Kyoushu\ProgressTracker;

use PHPUnit\Framework\TestCase;

class AbstractTrackerTest extends TestCase
{

    /**
     * @throws \Exception
     */
    public function testStart()
    {
        $tracker = new MockTracker();
        $now = new \DateTimeImmutable('now');
        $tracker->start();
        $this->assertInstanceOf(\DateTimeInterface::class, $tracker->getDateTimeStarted());
        $this->assertGreaterThanOrEqual($now, $tracker->getDateTimeStarted());
    }

    /**
     * @throws \Exception
     */
    public function testEnd()
    {
        $tracker = new MockTracker();
        $now = new \DateTimeImmutable('now');
        $tracker->end();
        $this->assertInstanceOf(\DateTimeInterface::class, $tracker->getDateTimeEnded());
        $this->assertGreaterThanOrEqual($now, $tracker->getDateTimeEnded());
    }

    /**
     * @throws \Exception
     */
    public function testGetSecondsElapsed()
    {
        $now = new \DateTimeImmutable('2018-01-01T13:00:00+00:00');
        $started = new \DateTimeImmutable('2018-01-01T12:00:00+00:00');

        $tracker = new MockTracker();
        $tracker->setDateTimeStarted($started);

        $secondsElapsed = $tracker->getSecondsElapsed($now);
        $this->assertEquals(3600, $secondsElapsed);
    }

    /**
     * @throws \Exception
     */
    public function testGetSecondsTotal()
    {
        $now = new \DateTimeImmutable('2018-01-01T13:00:00+00:00');
        $started = new \DateTimeImmutable('2018-01-01T12:00:00+00:00');

        $tracker = new MockTracker();
        $tracker->setDateTimeStarted($started);
        $tracker->setProgressPercentage(50);

        $secondsTotal = $tracker->getSecondsTotal($now);
        $this->assertEquals(7200, $secondsTotal);
    }

    /**
     * @throws \Exception
     */
    public function testGetDateTimeEta()
    {
        $now = new \DateTimeImmutable('2018-01-01T13:00:00+00:00');
        $started = new \DateTimeImmutable('2018-01-01T12:00:00+00:00');

        $tracker = new MockTracker();
        $tracker->setDateTimeStarted($started);
        $tracker->setProgressPercentage(50);

        $eta = $tracker->getDateTimeEta($now);
        $this->assertNotNull($eta);
        $this->assertEquals('2018-01-01 14:00:00', $eta->format('Y-m-d H:i:s'));
    }

}