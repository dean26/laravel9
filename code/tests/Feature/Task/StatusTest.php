<?php

use App\Dictionaries\UserRolesDictionary;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('authenticated user without permission can\'t change task status', function () {
    Task::unsetEventDispatcher();

    $task = Task::factory()->create();

    $fake_data = [
        'completed' => true,
    ];

    $user = User::factory()->create();
    $role_installer = Role::create(['name' => UserRolesDictionary::ROLE_INSTALLER]);
    $user->assignRole($role_installer->id);

    $response = $this->actingAs($user)->postJson("/api/tasks/{$task->id}/status", $fake_data);

    $response->assertStatus(403);
})->group('task');

it('authenticated user can change task status', function () {
    Task::unsetEventDispatcher();

    $task = Task::factory()->create();

    $fake_data = [
        'completed' => true,
    ];

    $user = User::factory()->create();
    $role_director = Role::create(['name' => UserRolesDictionary::ROLE_DIRECTOR]);
    $user->assignRole($role_director->id);

    $response = $this->actingAs($user)->postJson("/api/tasks/{$task->id}/status", $fake_data);

    $response->assertStatus(200);
})->group('task');
