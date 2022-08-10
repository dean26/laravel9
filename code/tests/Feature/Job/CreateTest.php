<?php

use App\Dictionaries\UserRolesDictionary;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Job;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('unauthenticated user\'t can store new job', function () {
    $response = $this->postJson('/api/jobs', []);
    $response->assertStatus(401);
})->group('job');

it('authenticated user can store new job', function () {
    Job::unsetEventDispatcher();
    Customer::withoutSyncingToSearch(function () {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        $customer = Customer::factory()->create();

        $faker = \Faker\Factory::create();

        $fake_data = [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'customer_id' => $customer->id,
            'start_date' => Carbon::today()->format('Y-m-d'),
            'expected_installation_date' => Carbon::tomorrow()->format('Y-m-d'),
        ];

        $user_logged = User::factory()->create();
        $role_director = Role::create(['name' => UserRolesDictionary::ROLE_DIRECTOR]);
        $user_logged->assignRole($role_director->id);

        $response = $this->actingAs($user_logged)->postJson('/api/jobs', $fake_data);

        $response->assertStatus(201);
    });
})->group('job');

it('authenticated user can\'t store a new job without customer who doesn\'t exist', function () {
    Customer::withoutSyncingToSearch(function () {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $fake_data = [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'customer_id' => null,
            'start_date' => Carbon::today()->format('Y-m-d'),
            'expected_installation_date' => Carbon::tomorrow()->format('Y-m-d'),
        ];

        $response = $this->actingAs(User::factory()->create())->postJson('/api/jobs', $fake_data);

        $response->assertStatus(422);
        $response->assertJsonFragment(['message' => 'The customer id field is required.']);
    });
})->group('job');
