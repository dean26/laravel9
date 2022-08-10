<?php

namespace App\Services;

use App\Jobs\JobStatusChangeNotification;
use App\Jobs\TaskStatusChangeNotification;
use App\Models\Job;
use App\Models\Task;

class NotificationService
{
    public function jobStatusChange(string $content, Job $job): void
    {
        //dispatch
        if (isset($job->status)) {
            JobStatusChangeNotification::dispatch($content, $job->id, $job->status)->delay(now()->addSeconds(15));
        }
    }

    public function taskStatusChange(string $content, Task $task): void
    {
        //dispatch
        if (isset($task->status)) {
            TaskStatusChangeNotification::dispatch($content, $task->id, $task->status)->delay(now()->addSeconds(15));
        }
    }
}
