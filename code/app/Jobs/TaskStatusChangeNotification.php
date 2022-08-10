<?php

namespace App\Jobs;

use App\Dictionaries\UserRolesDictionary;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskStatusChange;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TaskStatusChangeNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Task $taskModel;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private string $content, private int $task_id, private $new_status)
    {
        $this->taskModel = Task::find($this->task_id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->new_status != $this->taskModel->status) {
            return;
        }

        //get all directors
        $directors = User::role(UserRolesDictionary::ROLE_DIRECTOR)->get();

        foreach ($directors as $director) {
            $director->notify(new TaskStatusChange($this->content, $this->taskModel->id));
        }
    }
}
