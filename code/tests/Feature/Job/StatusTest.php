<?php

use App\Dictionaries\UserRolesDictionary;
use App\Models\Customer;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('authenticated user can change job status', function () {
    Job::unsetEventDispatcher();

    Customer::withoutSyncingToSearch(function () {
        $job = Job::factory()->create();

        $possible_statuses = \App\Services\JobService::getPossibleStatuses();
        shuffle($possible_statuses);

        $fake_data = [
            'status' => array_pop($possible_statuses),
        ];

        $user = User::factory()->create();
        $role_director = Role::create(['name' => UserRolesDictionary::ROLE_DIRECTOR]);
        $user->assignRole($role_director->id);

        $response = $this->actingAs($user)->postJson("/api/jobs/{$job->id}/status", $fake_data);

        $response->assertStatus(200);
    });
})->group('job');
