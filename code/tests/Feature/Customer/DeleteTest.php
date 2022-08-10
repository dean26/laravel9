<?php

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('unauthenticated user can\'t delete customer', function () {
    Customer::withoutSyncingToSearch(function () {
        $customer = Customer::factory()->create();
        $response = $this->deleteJson("/api/customers/{$customer->id}");
        $response->assertStatus(401);
    });
})->group('customers');

it('authenticated user can delete customer', function () {
    Customer::withoutSyncingToSearch(function () {
        $customer = Customer::factory()->create();
        $id = $customer->id;
        $response = $this->actingAs(User::factory()->create())->deleteJson("/api/customers/{$id}");

        $response->assertStatus(200);
        $this->assertNull(Customer::find($id));
    });
})->group('customers');
