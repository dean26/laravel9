<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('it doesn\'t allow login', function () {
    $fake_data = ['email' => 'test@cleancommit.io', 'password' => 'password'];

    $response = $this->postJson('/api/auth/login', $fake_data);

    $response->assertStatus(200)->assertJson([
        'message' => 'Invalid login details',
    ]);
});
