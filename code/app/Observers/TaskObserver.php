<?php

namespace App\Observers;

use App\Models\Task;
use App\Services\NotificationService;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function created(Task $task)
    {
        //add log
        $user = auth()->user();

        if($user){
            $task->logs()->create(
                [
                    'user_id' => $user->id,
                    'content' => __('messages.task.created', [
                            'name' => (string) $user, ]
                    ),
                ]
            );
        }
    }

    /**
     * Handle the Task "updated" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function updated(Task $task)
    {
        //add log
        $user = auth()->user();

        $status_change = ($task->getOriginal('status') != $task->status);

        if ($status_change) {
            $content = __('messages.task.status.updated', [
                'old_status' => $task->getOriginal('status') ?? 'empty',
                'new_status' => $task->status ?? 'empty',
                'name' => (string) $user,
            ]);
            //notifications
            (new NotificationService())->taskStatusChange($content, $task);
        } else {
            $content = __('messages.task.updated', [
                'name' => (string) $user, ]
            );
        }

        $task->logs()->create(
            [
                'user_id' => $user->id,
                'content' => $content,
            ]
        );
    }
}
