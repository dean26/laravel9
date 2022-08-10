<?php

namespace App\Observers;

use App\Models\Job;
use App\Services\NotificationService;
use App\Services\TaskService;

class JobObserver
{
    /**
     * Handle the Job "created" event.
     *
     * @param  \App\Models\Job  $job
     * @return void
     */
    public function created(Job $job)
    {
        //add log
        $user = auth()->user();
        if($user){
            $job->logs()->create(
                [
                    'user_id' => $user->id,
                    'content' => __('messages.job.created', [
                            'name' => (string) $user, ]
                    ),
                ]
            );
        }

        if ($job->expected_installation_date) {
            (new TaskService())->addAutomatedTasks($job);
        }
    }

    /**
     * Handle the Job "updated" event.
     *
     * @param  \App\Models\Job  $job
     * @return void
     */
    public function updated(Job $job)
    {
        //add log
        $user = auth()->user();

        $status_change = ($job->getOriginal('status') != $job->status);
        $expected_installation_date_change = ($job->getOriginal('expected_installation_date') != $job->expected_installation_date);

        if ($status_change) {
            $content = __('messages.job.status.updated', [
                'old_status' => $job->getOriginal('status') ?? 'empty',
                'new_status' => $job->status ?? 'empty',
                'name' => (string) $user,
            ]);
            //notifications
            (new NotificationService())->jobStatusChange($content, $job);
        } else {
            $content = __('messages.job.updated', [
                'name' => (string) $user, ]
            );
        }

        if ($expected_installation_date_change && $job->expected_installation_date) {
            (new TaskService())->addAutomatedTasks($job);
        }

        $job->logs()->create(
            [
                'user_id' => $user->id,
                'content' => $content,
            ]
        );
    }
}
