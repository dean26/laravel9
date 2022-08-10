<?php

namespace App\Jobs;

use App\Dictionaries\UserRolesDictionary;
use App\Models\Job;
use App\Models\User;
use App\Notifications\JobStatusChange;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class JobStatusChangeNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Job $jobModel;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private string $content, private int $job_id, private $new_status)
    {
        $this->jobModel = Job::find($this->job_id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->new_status != $this->jobModel->status) {
            return;
        }

        //get all directors
        $directors = User::role(UserRolesDictionary::ROLE_DIRECTOR)->get();

        foreach ($directors as $director) {
            $director->notify(new JobStatusChange($this->content, $this->jobModel->id));
        }
    }
}
