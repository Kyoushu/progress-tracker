<?php

namespace Tests\Kyoushu\ProgressTracker;

use Kyoushu\ProgressTracker\TaskTracker;
use Kyoushu\ProgressTracker\Exception\TaskTrackerException;
use PHPUnit\Framework\TestCase;

class TaskTrackerTest extends TestCase
{

    public function testCompleted()
    {
        $task = new TaskTracker();
        $this->assertFalse($task->isCompleted());
        $this->assertEquals(0, $task->getProgressPercentage());

        $task = new TaskTracker();
        $task->setCompleted(true);
        $this->assertTrue($task->isCompleted());
        $this->assertEquals(100, $task->getProgressPercentage());
    }

    /**
     * @throws TaskTrackerException
     */
    public function testGetProgressPercentageSubTasks()
    {
        $task = new TaskTracker();
        $task->defineSubTask('foo');
        $task->defineSubTask('bar');

        $this->assertEquals(0, $task->getProgressPercentage());
        $task->findSubTask('foo')->setCompleted(true);
        $this->assertEquals(50, $task->getProgressPercentage());
        $task->findSubTask('bar')->setCompleted(true);
        $this->assertEquals(100, $task->getProgressPercentage());
    }

    /**
     * @throws TaskTrackerException
     */
    public function testGetProgressPercentageSubTasksNested()
    {
        $task = new TaskTracker();
        $task->defineSubTask('1')->findSubTask('1')->defineSubTask('1.1')->defineSubTask('1.2');
        $task->defineSubTask('2')->findSubTask('2')->defineSubTask('2.1')->defineSubTask('2.2');
        $task->defineSubTask('3')->findSubTask('3')->defineSubTask('3.1')->defineSubTask('3.2');
        $task->defineSubTask('4')->findSubTask('4')->defineSubTask('4.1')->defineSubTask('4.2');

        $this->assertEquals(0, $task->getProgressPercentage());

        $task->findSubTask('1')->findSubTask('1.1')->setCompleted(true);
        $this->assertEquals(12.5, $task->getProgressPercentage());

        $task->findSubTask('1')->findSubTask('1.2')->setCompleted(true);
        $this->assertEquals(25, $task->getProgressPercentage());

        $task->findSubTask('2')->findSubTask('2.1')->setCompleted(true);
        $task->findSubTask('2')->findSubTask('2.2')->setCompleted(true);
        $this->assertEquals(50, $task->getProgressPercentage());

        $task->findSubTask('3')->findSubTask('3.1')->setCompleted(true);
        $task->findSubTask('3')->findSubTask('3.2')->setCompleted(true);
        $this->assertEquals(75, $task->getProgressPercentage());

        $task->findSubTask('4')->findSubTask('4.1')->setCompleted(true);
        $task->findSubTask('4')->findSubTask('4.2')->setCompleted(true);
        $this->assertEquals(100, $task->getProgressPercentage());
    }

}