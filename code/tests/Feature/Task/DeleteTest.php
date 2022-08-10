<?php

use App\Dictionaries\UserRolesDictionary;
use App\Models\Customer;
use App\Models\Job;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('unauthenticated user can\'t delete task', function () {
    Job::unsetEventDispatcher();
    Task::unsetEventDispatcher();

    Customer::withoutSyncingToSearch(function () {
        $task = Task::factory()->create();
        $response = $this->deleteJson("/api/tasks/{$task->id}");
        $response->assertStatus(401);
    });
})->group('task');

it('authenticated user-director can delete task', function () {
    Job::unsetEventDispatcher();
    Task::unsetEventDispatcher();

    Customer::withoutSyncingToSearch(function () {
        $task = Task::factory()->create();
        $id = $task->id;

        $user = User::factory()->create();
        $role_director = Role::create(['name' => UserRolesDictionary::ROLE_DIRECTOR]);
        $user->assignRole($role_director->id);

        $response = $this->actingAs($user)->deleteJson("/api/tasks/{$id}");

        $response->assertStatus(200);
        $this->assertNull(Task::find($id));
    });
})->group('task');

it('authenticated no director/author user can\'t delete task', function () {
    Job::unsetEventDispatcher();
    Task::unsetEventDispatcher();

    Customer::withoutSyncingToSearch(function () {
        $task = Task::factory()->create();
        $id = $task->id;

        $user = User::factory()->create();
        $role_director = Role::create(['name' => UserRolesDictionary::ROLE_WAREHOUSE]);
        $user->assignRole($role_director->id);

        $response = $this->actingAs($user)->deleteJson("/api/tasks/{$id}");
        $response->assertStatus(403);
    });
})->group('task');
