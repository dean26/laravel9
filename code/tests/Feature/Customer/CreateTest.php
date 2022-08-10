<?php

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('unauthenticated user\'t can store new customer', function () {
    $response = $this->postJson('/api/customers', []);
    $response->assertStatus(401);
})->group('customers');

it('authenticated user can store new customer', function () {
    Customer::withoutSyncingToSearch(function () {
        $faker = \Faker\Factory::create();

        $fake_data = [
            'name' => $faker->name(),
            'email' => $faker->email(),
            'phone' => $faker->phoneNumber(),
            'adress' => $faker->address(),
        ];

        $response = $this->actingAs(User::factory()->create())->postJson('/api/customers', $fake_data);

        $response->assertStatus(201);
    });
})->group('customers');
