<?php

use App\Dictionaries\UserRolesDictionary;
use App\Models\Customer;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('unauthenticated user can\'t delete job', function () {
    Job::unsetEventDispatcher();
    Customer::withoutSyncingToSearch(function () {
        $job = Job::factory()->create();
        $response = $this->deleteJson("/api/jobs/{$job->id}");
        $response->assertStatus(401);
    });
})->group('job');

it('authenticated user can delete job', function () {
    Job::unsetEventDispatcher();
    Customer::withoutSyncingToSearch(function () {
        $job = Job::factory()->create();
        $id = $job->id;

        $user = User::factory()->create();
        $role_director = Role::create(['name' => UserRolesDictionary::ROLE_DIRECTOR]);
        $user->assignRole($role_director->id);

        $response = $this->actingAs($user)->deleteJson("/api/jobs/{$id}");

        $response->assertStatus(200);
        $this->assertNull(Job::find($id));
    });
})->group('job');
