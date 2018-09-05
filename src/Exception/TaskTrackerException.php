<?php

namespace Kyoushu\ProgressTracker\Exception;

class TaskTrackerException extends ProgressTrackerException
{

    const CODE_SUB_TASK_ALREADY_EXISTS = 1;
    const CODE_SUB_TASK_MISSING = 2;

}