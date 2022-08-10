<?php

use App\Dictionaries\UserRolesDictionary;
use App\Models\Job;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('unauthenticated user\'t can store new task', function () {
    Job::unsetEventDispatcher();
    Task::unsetEventDispatcher();

    $job = Job::factory()->create();

    $user = User::factory()->create();

    $faker = \Faker\Factory::create();

    $fake_data = [
        'user_id' => $user->id,
        'content' => $faker->text(),
        'deadline' => Carbon::today()->format('Y-m-d'),
    ];

    $response = $this->postJson("/api/jobs/{$job->id}/tasks", $fake_data);
    $response->assertStatus(401);
})->group('task');

it('authenticated user\'t can store new task', function () {
    Job::unsetEventDispatcher();
    Task::unsetEventDispatcher();

    $job = Job::factory()->create();

    $user = User::factory()->create();
    $user_director = User::factory()->create();
    $role_director = Role::create(['name' => UserRolesDictionary::ROLE_DIRECTOR]);
    $user_director->assignRole($role_director->id);

    $faker = \Faker\Factory::create();

    $fake_data = [
        'user_id' => $user->id,
        'content' => $faker->text(),
        'deadline' => Carbon::today()->format('Y-m-d'),
    ];

    $response = $this->actingAs($user_director)->postJson("/api/jobs/{$job->id}/tasks", $fake_data);
    $response->assertStatus(201);
})->group('task');
