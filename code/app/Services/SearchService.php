<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\User;
use App\Traits\ServiceTrait;

class SearchService
{
    use ServiceTrait;

    public function __construct()
    {
        return $this;
    }

    /**
     * @param  array  $data
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getData(array $data): \Illuminate\Database\Eloquent\Collection
    {
        $customers = Customer::search($data['query'])->get();
        $users = User::search($data['query'])->get();

        return $customers->merge($users);
    }
}
