<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository implements \App\Interfaces\RepositoryInterface
{
    public function getAll()
    {
        return User::all();
    }

    public function getById(int $id)
    {
        return User::findOrFail($id);
    }

    public function delete(int $id)
    {
        User::destroy($id);
    }

    public function deleteMultiple(array $ids)
    {
        User::destroy($ids);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update($id, array $data)
    {
        $user = $this->getById($id);
        $user->update($data);

        return $user;
    }
}
