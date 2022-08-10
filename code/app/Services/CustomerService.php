<?php

namespace App\Services;

use App\Models\Customer;
use App\Traits\ServiceTrait;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerService
{
    use ServiceTrait;

    public function __construct()
    {
        return $this;
    }

    public function paginateByArrayData(array $data): LengthAwarePaginator
    {
        if (isset($data['query'])) {
            return Customer::search($data['query'])->paginate($this->getPerPage($data));
        }

        $query = Customer::query()->select('*');
        (isset($data['sort_by'])) ? $query->orderBy($data['sort_by'], (array_key_exists('order_by', $data) ? $data['order_by'] : 'desc')) : $query->orderBy('id', 'desc');

        return $query->paginate($this->getPerPage($data));
    }
}
