<?php

use App\Models\Customer;
use App\Repositories\CustomerRepository;

test('customer repository create test', function () {
    $mock = mock(CustomerRepository::class)
        ->shouldReceive(['create' => Customer::class])
        ->getMock();

    expect($mock->create([]))->toBe(Customer::class);
})->group('customers');

test('customer repository update test', function () {
    $mock = mock(CustomerRepository::class)
        ->shouldReceive(['update' => Customer::class])
        ->getMock();

    expect($mock->update(1, []))->toBe(Customer::class);
})->group('customers');

test('customer repository delete test', function () {
    $mock = mock(CustomerRepository::class)
        ->shouldReceive(['delete' => null])
        ->getMock();

    expect($mock->delete(1))->toBeNull();
})->group('customers');

test('customer repository multiple delete test', function () {
    $mock = mock(CustomerRepository::class)
        ->shouldReceive(['deleteMultiple' => null])
        ->getMock();

    expect($mock->deleteMultiple([]))->toBeNull();
})->group('customers');
