<?php

namespace App\Repositories;

use App\Models\Guide;

class GuideRepository implements \App\Interfaces\RepositoryInterface
{

    public function getAll()
    {
        return Guide::all();
    }

    public function getById(int $id)
    {
        return Guide::findOrFail($id);
    }

    public function delete(int $id)
    {
        Guide::destroy($id);
    }

    public function deleteMultiple(array $ids)
    {
        Guide::destroy($ids);
    }

    public function create(array $data)
    {
        return Guide::create($data);
    }

    public function update($id, array $data)
    {
        $guide = $this->getById($id);
        $guide->update($data);

        return $guide;
    }
}
