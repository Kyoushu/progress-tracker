<?php

namespace Kyoushu\ProgressTracker;

use Kyoushu\ProgressTracker\Exception\TaskTrackerException;

class TaskTracker extends AbstractTracker
{

    /**
     * @var bool
     */
    protected $completed = false;

    /**
     * @var TaskTracker[]
     */
    protected $subTasks = [];

    public function getProgressPercentage(): float
    {
        if($this->completed) return 100;

        $totalSubTasks = count($this->subTasks);
        if($totalSubTasks === 0) return 0;

        $progressPercentage = 0;

        foreach($this->subTasks as $subTask){
            $progressPercentage += (100 / $totalSubTasks) * ($subTask->getProgressPercentage() / 100);
        }

        return $progressPercentage;
    }

    /**
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->completed;
    }

    /**
     * @param bool $completed
     * @return $this
     */
    public function setCompleted(bool $completed)
    {
        $this->completed = $completed;
        return $this;
    }

    protected function subTaskExists(string $name): bool
    {
        return isset($this->subTasks[$name]);
    }

    /**
     * @param string $name
     * @throws TaskTrackerException
     */
    protected function assertSubTaskNotExists(string $name)
    {
        if(!$this->subTaskExists($name)) return;
        throw new TaskTrackerException(sprintf('Sub-task "%s" already exists', TaskTrackerException::CODE_SUB_TASK_ALREADY_EXISTS));
    }

    /**
     * @param string $name
     * @throws TaskTrackerException
     */
    protected function assertSubTaskExists(string $name)
    {
        if($this->subTaskExists($name)) return;
        throw new TaskTrackerException(sprintf('Sub-task "%s" does not exist', TaskTrackerException::CODE_SUB_TASK_MISSING));
    }

    /**
     * @param string $name
     * @return TaskTracker
     * @throws TaskTrackerException
     */
    public function defineSubTask(string $name): self
    {
        $this->assertSubTaskNotExists($name);
        $this->subTasks[$name] = new TaskTracker();
        return $this;
    }

    /**
     * @param string $name
     * @return TaskTracker
     * @throws TaskTrackerException
     */
    public function findSubTask(string $name): TaskTracker
    {
        $this->assertSubTaskExists($name);
        return $this->subTasks[$name];
    }

}