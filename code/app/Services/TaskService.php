<?php

namespace App\Services;

use App\Dictionaries\UserRolesDictionary;
use App\Models\Job;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TaskService
{
    public function store(array $data, Model $model): Model
    {
        $data['author_id'] = auth()->user()->id;

        return $model->tasks()->create($data);
    }

    public function addAutomatedTasks(Job $job)
    {
        $worehouseUsers = User::role(UserRolesDictionary::ROLE_WAREHOUSE)->get();

        $user_auth = auth()->user();

        foreach ($worehouseUsers as $user) {
            $tasks = DB::table('automated_tasks')->where('item_id', $job->item_id)->get();

            foreach ($tasks as $task) {
                $job->tasks()->create([
                    'author_id' => $user_auth? $user_auth->id : $user->id,
                    'user_id' => $user->id,
                    'deadline' => Carbon::create($job->expected_installation_date)->addDay('-'.$task->days)->format('Y-m-d'),
                    'content' => $task->content,
                ]);
            }
        }
    }
}
