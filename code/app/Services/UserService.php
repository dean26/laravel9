<?php

namespace App\Services;

use App\Models\User;
use App\Traits\ServiceTrait;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    use ServiceTrait;

    public function __construct()
    {
        return $this;
    }

    public function paginateByArrayData(array $data): LengthAwarePaginator
    {
        if (isset($data['query'])) {
            return User::search($data['query'])->paginate($this->getPerPage($data));
        }

        $query = User::query()->select('*');
        (isset($data['sort_by'])) ? $query->orderBy($data['sort_by'], (array_key_exists('order_by', $data) ? $data['order_by'] : 'desc')) : $query->orderBy('id', 'desc');

        return $query->paginate($this->getPerPage($data));
    }
}
