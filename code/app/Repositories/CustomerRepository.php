<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Models\Customer;

class CustomerRepository implements RepositoryInterface
{
    public function getAll()
    {
        return Customer::all();
    }

    public function getById(int $id)
    {
        return Customer::findOrFail($id);
    }

    public function delete(int $id)
    {
        Customer::destroy($id);
    }

    public function deleteMultiple(array $ids)
    {
        Customer::destroy($ids);
    }

    public function create(array $data)
    {
        return Customer::create($data);
    }

    public function update($id, array $data)
    {
        $customer = $this->getById($id);
        $customer->update($data);

        return $customer;
    }
}
