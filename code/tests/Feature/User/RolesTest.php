<?php

use App\Dictionaries\UserRolesDictionary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('has non director user can\'t store a user', function () {
    $role_installer = Role::create(['name' => UserRolesDictionary::ROLE_INSTALLER]);

    $user = User::factory()->create();
    $user->assignRole($role_installer->id);

    $faker = \Faker\Factory::create();
    $password = $faker->password(8);

    $fake_data = [
        'name' => $faker->name(),
        'email' => $faker->email(),
        'phone' => $faker->phoneNumber(),
        'adress' => $faker->address(),
        'password' => $password,
        'password_confirmation' => $password,
    ];

    $response = $this->actingAs($user)->postJson('/api/users', $fake_data);

    $response->assertStatus(403);
})->group('roles');

it('has non director user can\'t change random user password', function () {
    $role_installer = Role::create(['name' => UserRolesDictionary::ROLE_INSTALLER]);
    $role_director = Role::create(['name' => UserRolesDictionary::ROLE_DIRECTOR]);

    $director = User::factory()->create();
    $director->assignRole($role_director->id);

    $user = User::factory()->create();
    $user->assignRole($role_installer->id);

    $faker = \Faker\Factory::create();
    $password = $faker->password(8);

    $fake_data = [
        'password' => $password,
        'password_confirmation' => $password,
    ];

    $response = $this->actingAs($user)->putJson("/api/users/{$director->id}/password", $fake_data);

    $response->assertStatus(403);
})->group('roles');
