<?php

namespace App\Repositories;

use App\Models\Flag;

class FlagRepository implements \App\Interfaces\RepositoryInterface
{
    public function getAll()
    {
        return Flag::all();
    }

    public function getById(int $id)
    {
        return Flag::findOrFail($id);
    }

    public function delete(int $id)
    {
        Flag::destroy($id);
    }

    public function deleteMultiple(array $ids)
    {
        Flag::destroy($ids);
    }

    public function create(array $data)
    {
        return Flag::create($data);
    }

    public function update($id, array $data)
    {
        $flag = $this->getById($id);
        $flag->update($data);

        return $flag;
    }
}
